<?php

namespace App\Support;

use App\Models\Disposal;
use App\Models\DisposalLine;
use App\Models\InventoryItem;
use App\Models\InventoryMovement;
use App\Models\PropertyTransaction;
use App\Models\PropertyTransactionLine;
use App\Models\Transfer;
use App\Models\TransferLine;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InventoryManager
{
    public static function recordIssuance(PropertyTransaction $transaction, PropertyTransactionLine $line, ?int $actedBy = null): void
    {
        if ($line->inventory_item_id) {
            $inventory = InventoryItem::findOrFail($line->inventory_item_id);
            $inventory->update([
                'item_id' => $line->item_id ?: $inventory->item_id,
                'property_transaction_line_id' => $line->id,
                'office_id' => $transaction->office_id,
                'fund_cluster_id' => $transaction->fund_cluster_id,
                'current_employee_id' => $transaction->employee_id,
                'accountable_name' => $transaction->employee?->name,
                'status' => 'issued',
                'issued_at' => $transaction->transaction_date,
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $inventory->id,
                'movement_type' => 'issued',
                'reference_type' => PropertyTransaction::class,
                'reference_id' => $transaction->id,
                'to_employee_id' => $transaction->employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $transaction->transaction_date,
                'remarks' => 'Issued from inventory via '.$transaction->control_no,
            ]);

            self::syncSourceLineLifecycle($line->id);

            return;
        }

        if ($line->item_id) {
            $stockItems = InventoryItem::query()
                ->where('item_id', $line->item_id)
                ->where('status', 'in_stock')
                ->orderBy('id')
                ->get();

            if ($stockItems->count() < (int) $line->quantity) {
                $requested = (int) $line->quantity;
                $available = (int) $stockItems->count();

                self::validationError(
                    "Insufficient unissued stock for issuance line: {$line->description}. Requested {$requested}, available {$available}."
                );
            }

            $stockItems->take((int) $line->quantity)->each(function (InventoryItem $inventory) use ($transaction, $line, $actedBy): void {
                $inventory->update([
                    'property_transaction_line_id' => $line->id,
                    'office_id' => $transaction->office_id,
                    'fund_cluster_id' => $transaction->fund_cluster_id,
                    'current_employee_id' => $transaction->employee_id,
                    'accountable_name' => $transaction->employee?->name,
                    'status' => 'issued',
                    'issued_at' => $transaction->transaction_date,
                ]);

                InventoryMovement::create([
                    'inventory_item_id' => $inventory->id,
                    'movement_type' => 'issued',
                    'reference_type' => PropertyTransaction::class,
                    'reference_id' => $transaction->id,
                    'to_employee_id' => $transaction->employee_id,
                    'acted_by' => $actedBy,
                    'movement_date' => $transaction->transaction_date,
                    'remarks' => 'Issued from inventory via '.$transaction->control_no,
                ]);
            });

            self::syncSourceLineLifecycle($line->id);

            return;
        }

        for ($i = 0; $i < (int) $line->quantity; $i++) {
            $inventory = InventoryItem::create([
                'item_id' => $line->item_id,
                'property_transaction_line_id' => $line->id,
                'office_id' => $transaction->office_id,
                'fund_cluster_id' => $transaction->fund_cluster_id,
                'current_employee_id' => $transaction->employee_id,
                'accountable_name' => $transaction->employee?->name,
                'inventory_code' => self::nextInventoryCode(),
                'qr_token' => (string) Str::uuid(),
                'description' => $line->description,
                'unit' => $line->unit,
                'unit_cost' => $line->unit_cost,
                'classification' => $line->classification,
                'property_no' => $line->property_no,
                'date_acquired' => $line->date_acquired ?? $transaction->transaction_date,
                'status' => 'issued',
                'issued_at' => $transaction->transaction_date,
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $inventory->id,
                'movement_type' => 'issued',
                'reference_type' => PropertyTransaction::class,
                'reference_id' => $transaction->id,
                'to_employee_id' => $transaction->employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $transaction->transaction_date,
                'remarks' => 'Auto-created from approved issuance '.$transaction->control_no,
            ]);
        }

        self::syncSourceLineLifecycle($line->id);
    }

    public static function recordTransfer(Transfer $transfer, TransferLine $line, ?int $actedBy = null): void
    {
        if ($line->inventory_item_id) {
            $item = InventoryItem::findOrFail($line->inventory_item_id);
            if ($item->status === 'disposed') {
                self::validationError('Cannot transfer disposed inventory item.');
            }

            if ((int) $item->current_employee_id !== (int) $transfer->from_employee_id) {
                self::validationError('Inventory item holder does not match transfer source.');
            }

            $item->update([
                'current_employee_id' => $transfer->to_employee_id,
                'accountable_name' => $transfer->toEmployee?->name,
                'status' => 'issued',
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'movement_type' => 'transferred',
                'reference_type' => Transfer::class,
                'reference_id' => $transfer->id,
                'from_employee_id' => $transfer->from_employee_id,
                'to_employee_id' => $transfer->to_employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $transfer->transfer_date,
                'remarks' => 'Transferred via '.$transfer->document_type.' '.$transfer->control_no
                    .($line->reference_no ? ' | Source Issuance: '.$line->reference_no : ''),
            ]);

            self::syncSourceLineLifecycle($item->property_transaction_line_id);

            return;
        }

        $items = self::resolveItemsForTransfer($transfer, $line);
        if ($items->count() < $line->quantity) {
            $requested = (int) $line->quantity;
            $available = (int) $items->count();
            self::validationError(
                "Insufficient inventory for transfer line: {$line->description}. Requested {$requested}, available {$available}."
            );
        }

        $items->take($line->quantity)->each(function (InventoryItem $item) use ($transfer, $line, $actedBy): void {
            $item->update([
                'current_employee_id' => $transfer->to_employee_id,
                'accountable_name' => $transfer->toEmployee?->name,
                'status' => 'issued',
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'movement_type' => 'transferred',
                'reference_type' => Transfer::class,
                'reference_id' => $transfer->id,
                'from_employee_id' => $transfer->from_employee_id,
                'to_employee_id' => $transfer->to_employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $transfer->transfer_date,
                'remarks' => 'Transferred via '.$transfer->document_type.' '.$transfer->control_no
                    .($line->reference_no ? ' | Source Issuance: '.$line->reference_no : ''),
            ]);
        });

        self::syncSourceLineLifecycle($line->property_transaction_line_id);
    }

    public static function recordDisposal(Disposal $disposal, DisposalLine $line, ?int $actedBy = null): void
    {
        if ($line->inventory_item_id) {
            $item = InventoryItem::findOrFail($line->inventory_item_id);
            if ($item->status === 'disposed') {
                self::validationError('Inventory item already disposed.');
            }

            $item->update([
                'status' => 'disposed',
                'current_employee_id' => null,
                'accountable_name' => $disposal->employee?->name,
                'disposed_at' => $disposal->disposal_date,
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'movement_type' => 'disposed',
                'reference_type' => Disposal::class,
                'reference_id' => $disposal->id,
                'from_employee_id' => $disposal->employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $disposal->disposal_date,
                'remarks' => 'Disposed via '.$disposal->document_type.' '.$disposal->control_no,
            ]);

            self::syncSourceLineLifecycle($item->property_transaction_line_id);

            return;
        }

        $items = self::resolveItemsForDisposal($disposal, $line);
        if ($items->count() < $line->quantity) {
            $requested = (int) $line->quantity;
            $available = (int) $items->count();
            self::validationError(
                "Insufficient inventory for disposal line: {$line->particulars}. Requested {$requested}, available {$available}."
            );
        }

        $items->take($line->quantity)->each(function (InventoryItem $item) use ($disposal, $actedBy): void {
            $item->update([
                'status' => 'disposed',
                'current_employee_id' => null,
                'accountable_name' => $disposal->employee?->name,
                'disposed_at' => $disposal->disposal_date,
            ]);

            InventoryMovement::create([
                'inventory_item_id' => $item->id,
                'movement_type' => 'disposed',
                'reference_type' => Disposal::class,
                'reference_id' => $disposal->id,
                'from_employee_id' => $disposal->employee_id,
                'acted_by' => $actedBy,
                'movement_date' => $disposal->disposal_date,
                'remarks' => 'Disposed via '.$disposal->document_type.' '.$disposal->control_no,
            ]);
        });

        self::syncSourceLineLifecycle($line->property_transaction_line_id);
    }

    /**
     * @return \Illuminate\Support\Collection<int, InventoryItem>
     */
    private static function resolveItemsForTransfer(Transfer $transfer, TransferLine $line): Collection
    {
        $query = InventoryItem::query()
            ->where('status', 'issued')
            ->orderBy('id');

        if ($line->property_transaction_line_id) {
            $query->where('property_transaction_line_id', $line->property_transaction_line_id);
        } else {
            $query->where('description', $line->description);
        }

        $query->where('current_employee_id', $transfer->from_employee_id);

        return $query->get();
    }

    /**
     * @return \Illuminate\Support\Collection<int, InventoryItem>
     */
    private static function resolveItemsForDisposal(Disposal $disposal, DisposalLine $line): Collection
    {
        $query = InventoryItem::query()
            ->whereIn('status', ['in_stock', 'issued'])
            ->orderBy('id');

        if ($line->property_transaction_line_id) {
            $query->where('property_transaction_line_id', $line->property_transaction_line_id);
        } else {
            $query->where('description', $line->particulars);
        }

        return $query->where(function ($sub) use ($disposal): void {
            $sub->whereNull('current_employee_id')
                ->orWhere('current_employee_id', $disposal->employee_id);
        })->get();
    }

    private static function nextInventoryCode(): string
    {
        do {
            $code = 'INV-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4));
        } while (InventoryItem::where('inventory_code', $code)->exists());

        return $code;
    }

    private static function validationError(string $message): never
    {
        throw ValidationException::withMessages([
            'inventory' => $message,
        ]);
    }

    public static function syncSourceLineLifecycle(?int $sourceLineId): void
    {
        if (!$sourceLineId) {
            return;
        }

        $sourceLine = PropertyTransactionLine::find($sourceLineId);
        if (!$sourceLine) {
            return;
        }

        $totalTracked = InventoryItem::query()
            ->where('property_transaction_line_id', $sourceLineId)
            ->count();

        if ($totalTracked === 0) {
            return;
        }

        $activeTracked = InventoryItem::query()
            ->where('property_transaction_line_id', $sourceLineId)
            ->whereIn('status', ['in_stock', 'issued'])
            ->count();

        $status = $activeTracked > 0 ? 'active' : 'disposed';

        if ($sourceLine->item_status !== $status) {
            $sourceLine->update(['item_status' => $status]);
        }
    }
}
