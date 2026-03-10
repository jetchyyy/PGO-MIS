<?php

namespace App\Support;

use App\Models\Disposal;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class DocumentCatalog
{
    private const DOCUMENTS = [
        'PAR' => ['code' => 'PAR', 'title' => 'Property Acknowledgement Receipt', 'template' => 'par'],
        'ICS' => ['code' => 'ICS', 'title' => 'Inventory Custodian Slip', 'template' => 'ics'],
        'SPLV' => ['code' => 'SPLV', 'title' => 'Semi-Expendable Property Low Value', 'template' => 'classification_splv'],
        'SPHV' => ['code' => 'SPHV', 'title' => 'Semi-Expendable Property High Value', 'template' => 'classification_sphv'],
        'PTR' => ['code' => 'PTR', 'title' => 'Property Transfer Report', 'template' => 'ptr'],
        'ITR' => ['code' => 'ITR', 'title' => 'Inventory Transfer Report', 'template' => 'itr'],
        'IIRUP' => ['code' => 'IIRUP', 'title' => 'Inventory and Inspection Report of Unserviceable Property', 'template' => 'iirup'],
        'IIRUSP' => ['code' => 'IIRUSP', 'title' => 'Inventory and Inspection Report of Unserviceable Semi-Expendable Property', 'template' => 'iirusp'],
        'RRSEP' => ['code' => 'RRSEP', 'title' => 'Receipt of Returned Semi-Expendable Property', 'template' => 'rrsep'],
        'WMR' => ['code' => 'WMR', 'title' => 'Waste Materials Report', 'template' => 'wmr'],
        'PC' => ['code' => 'PC', 'title' => 'Property Card', 'template' => 'property_card'],
        'SPC' => ['code' => 'SPC', 'title' => 'Semi-Expendable Property Card', 'template' => 'semi_property_card'],
        'REGSPI' => ['code' => 'REGSPI', 'title' => 'Registry of Semi-Expendable Property Issued', 'template' => 'regspi'],
        'TAG' => ['code' => 'TAG', 'title' => 'Property Sticker / Tag', 'template' => 'sticker'],
    ];

    public static function all(): array
    {
        return self::DOCUMENTS;
    }

    public static function templatesFor(Model $record): array
    {
        return match (true) {
            $record instanceof PropertyTransaction => self::templatesForIssuance($record),
            $record instanceof Transfer => self::templatesForTransfer($record),
            $record instanceof Disposal => self::templatesForDisposal($record),
            default => throw new InvalidArgumentException('Unsupported record for document catalog.'),
        };
    }

    public static function templatesForIssuance(PropertyTransaction $issuance): array
    {
        $documents = [
            self::DOCUMENTS['TAG'],
        ];

        if ($issuance->document_type === 'PAR') {
            $documents[] = self::DOCUMENTS['PAR'];
            $documents[] = self::DOCUMENTS['PC'];
        }

        if (in_array($issuance->document_type, ['ICS-SPLV', 'ICS-SPHV'], true)) {
            $documents[] = self::DOCUMENTS['ICS'];
            $documents[] = self::DOCUMENTS[$issuance->document_type === 'ICS-SPHV' ? 'SPHV' : 'SPLV'];
            $documents[] = self::DOCUMENTS['SPC'];
            $documents[] = self::DOCUMENTS['REGSPI'];
        }

        return array_values($documents);
    }

    public static function templatesForTransfer(Transfer $transfer): array
    {
        return [
            self::DOCUMENTS[$transfer->document_type],
            self::DOCUMENTS['TAG'],
        ];
    }

    public static function templatesForDisposal(Disposal $disposal): array
    {
        $documents = [
            self::DOCUMENTS[$disposal->document_type],
            self::DOCUMENTS['WMR'],
        ];

        if ($disposal->document_type === Disposal::DOCUMENT_TYPE_RRSEP) {
            $documents[] = self::DOCUMENTS['IIRUSP'];
        } else {
            $documents[] = self::DOCUMENTS['RRSEP'];
        }

        return array_values($documents);
    }

    public static function templateFor(Model $record, string $template): array
    {
        foreach (self::templatesFor($record) as $document) {
            if ($document['template'] === $template) {
                return $document;
            }
        }

        throw new InvalidArgumentException("Unsupported template [{$template}] for record.");
    }
}
