<?php

namespace App\Support;

use App\Models\AccountabilityHeader;
use App\Models\AccountabilityLine;
use App\Models\Disposal;
use App\Models\DisposalLine;
use App\Models\PropertyCard;
use App\Models\PropertyCardEntry;
use App\Models\PropertyTransaction;
use App\Models\PropertyTransactionLine;
use App\Models\RegSPIEntry;
use App\Models\SemiExpendableCard;
use App\Models\SemiExpendableCardEntry;
use App\Models\Transfer;
use App\Models\TransferLine;

class WorkflowUpdater
{
    public static function applyIssuance(PropertyTransaction $transaction): void
    {
        foreach ($transaction->lines as $line) {
            $isPpe = $line->classification === 'ppe';

            if ($isPpe) {
                $card = PropertyCard::firstOrCreate(
                    [
                        'description' => $line->description,
                        'property_no' => $line->property_no,
                        'office_id' => $transaction->office_id,
                        'fund_cluster_id' => $transaction->fund_cluster_id,
                    ],
                    [
                        'card_no' => 'PC-'.date('Y').'-'.str_pad((string) (PropertyCard::max('id') + 1), 4, '0', STR_PAD_LEFT),
                        'balance_qty' => 0,
                        'balance_amount' => 0,
                    ]
                );

                $newQty = $card->balance_qty + $line->quantity;
                $newAmount = (float) $card->balance_amount + (float) $line->total_cost;

                PropertyCardEntry::create([
                    'property_card_id' => $card->id,
                    'source_type' => PropertyTransactionLine::class,
                    'source_id' => $line->id,
                    'entry_date' => $transaction->transaction_date,
                    'reference_no' => $transaction->control_no,
                    'qty_in' => $line->quantity,
                    'qty_out' => 0,
                    'running_balance_qty' => $newQty,
                    'amount_in' => $line->total_cost,
                    'amount_out' => 0,
                    'running_balance_amount' => $newAmount,
                    'remarks' => 'Issuance approved',
                ]);

                $card->update(['balance_qty' => $newQty, 'balance_amount' => $newAmount]);
            } else {
                $card = SemiExpendableCard::firstOrCreate(
                    [
                        'description' => $line->description,
                        'property_no' => $line->property_no,
                        'office_id' => $transaction->office_id,
                        'fund_cluster_id' => $transaction->fund_cluster_id,
                    ],
                    [
                        'card_no' => 'SPC-'.date('Y').'-'.str_pad((string) (SemiExpendableCard::max('id') + 1), 4, '0', STR_PAD_LEFT),
                        'balance_qty' => 0,
                        'balance_amount' => 0,
                    ]
                );

                $newQty = $card->balance_qty + $line->quantity;
                $newAmount = (float) $card->balance_amount + (float) $line->total_cost;

                SemiExpendableCardEntry::create([
                    'semi_expendable_card_id' => $card->id,
                    'source_type' => PropertyTransactionLine::class,
                    'source_id' => $line->id,
                    'entry_date' => $transaction->transaction_date,
                    'reference_no' => $transaction->control_no,
                    'qty_in' => $line->quantity,
                    'qty_out' => 0,
                    'running_balance_qty' => $newQty,
                    'amount_in' => $line->total_cost,
                    'amount_out' => 0,
                    'running_balance_amount' => $newAmount,
                    'remarks' => 'Issuance approved',
                ]);

                $card->update(['balance_qty' => $newQty, 'balance_amount' => $newAmount]);

                // Log RegSPI entry for semi-expendable items
                RegSPIEntry::create([
                    'semi_expendable_card_id' => $card->id,
                    'property_transaction_id' => $transaction->id,
                    'property_transaction_line_id' => $line->id,
                    'ics_no' => $transaction->control_no,
                    'description' => $line->description,
                    'employee_id' => $transaction->employee_id,
                    'office_id' => $transaction->office_id,
                    'fund_cluster_id' => $transaction->fund_cluster_id,
                    'quantity_issued' => $line->quantity,
                    'unit_cost' => $line->unit_cost,
                    'total_cost' => $line->total_cost,
                    'property_no' => $line->property_no,
                    'issue_date' => $transaction->transaction_date,
                    'classification' => $line->classification,
                    'remarks' => 'Auto-generated on approval',
                ]);
            }

            $header = AccountabilityHeader::firstOrCreate(
                [
                    'employee_id' => $transaction->employee_id,
                    'office_id' => $transaction->office_id,
                    'fund_cluster_id' => $transaction->fund_cluster_id,
                    'status' => 'active',
                ],
                [
                    'reference_no' => $transaction->control_no,
                ]
            );

            AccountabilityLine::create([
                'accountability_header_id' => $header->id,
                'source_line_type' => PropertyTransactionLine::class,
                'source_line_id' => $line->id,
                'quantity' => $line->quantity,
                'unit' => $line->unit,
                'description' => $line->description,
                'property_no' => $line->property_no,
                'unit_cost' => $line->unit_cost,
                'amount' => $line->total_cost,
                'status' => 'active',
            ]);
        }
    }

    public static function applyTransfer(Transfer $transfer): void
    {
        foreach ($transfer->lines as $line) {
            if ($line->sourceLine) {
                $status = $line->sourceLine->item_status;
                if ($status === 'disposed') {
                    abort(422, 'Cannot transfer disposed items.');
                }
                $line->sourceLine->update(['item_status' => 'transferred']);
            }

            $fromHeader = AccountabilityHeader::where('employee_id', $transfer->from_employee_id)
                ->where('fund_cluster_id', $transfer->fund_cluster_id)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if ($fromHeader) {
                AccountabilityLine::where('accountability_header_id', $fromHeader->id)
                    ->where('description', $line->description)
                    ->where('status', 'active')
                    ->update(['status' => 'transferred']);
            }

            $toHeader = AccountabilityHeader::firstOrCreate(
                [
                    'employee_id' => $transfer->to_employee_id,
                    'office_id' => $transfer->fromEmployee->office_id,
                    'fund_cluster_id' => $transfer->fund_cluster_id,
                    'status' => 'active',
                ],
                [
                    'reference_no' => $transfer->control_no,
                ]
            );

            AccountabilityLine::create([
                'accountability_header_id' => $toHeader->id,
                'source_line_type' => TransferLine::class,
                'source_line_id' => $line->id,
                'quantity' => $line->quantity,
                'unit' => $line->unit,
                'description' => $line->description,
                'unit_cost' => $line->quantity > 0 ? ((float) $line->amount / $line->quantity) : 0,
                'amount' => $line->amount,
                'status' => 'active',
            ]);
        }
    }

    public static function applyDisposal(Disposal $disposal): void
    {
        foreach ($disposal->lines as $line) {
            if ($line->sourceLine && $line->sourceLine->item_status !== 'active') {
                abort(422, 'Cannot dispose unissued or inactive items.');
            }

            if ($line->sourceLine) {
                $line->sourceLine->update(['item_status' => 'disposed']);
            }

            $headers = AccountabilityHeader::where('employee_id', $disposal->employee_id)
                ->where('fund_cluster_id', $disposal->fund_cluster_id)
                ->pluck('id');

            AccountabilityLine::whereIn('accountability_header_id', $headers)
                ->where('description', $line->particulars)
                ->where('status', 'active')
                ->update(['status' => 'disposed']);
        }
    }
}
