<?php

namespace App\Support;

use App\Models\Disposal;
use App\Models\DocumentControl;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Model;

class DocumentControlRegistry
{
    public static function ensureFor(Model $record): void
    {
        $templates = DocumentCatalog::templatesFor($record);
        $sourceDate = self::sourceDateFor($record);

        foreach ($templates as $document) {
            $record->documentControls()->firstOrCreate(
                ['template_name' => $document['template']],
                [
                    'document_code' => $document['code'],
                    'document_title' => $document['title'],
                    'control_no' => NumberGenerator::nextDocument($document['code'], $sourceDate),
                    'generated_on' => $sourceDate,
                ]
            );
        }
    }

    public static function listFor(Model $record): array
    {
        self::ensureFor($record);
        $record->load('documentControls');

        return $record->documentControls
            ->sortBy('id')
            ->map(fn (DocumentControl $document): array => [
                'key' => $document->template_name,
                'template' => $document->template_name,
                'code' => $document->document_code,
                'title' => $document->document_title,
                'control_no' => $document->control_no,
                'printable' => ! str_starts_with($document->template_name, 'classification_'),
            ])
            ->values()
            ->all();
    }

    public static function findFor(Model $record, string $template): ?DocumentControl
    {
        self::ensureFor($record);

        return $record->documentControls()
            ->where('template_name', $template)
            ->first();
    }

    private static function sourceDateFor(Model $record): string
    {
        return match (true) {
            $record instanceof PropertyTransaction => $record->transaction_date->toDateString(),
            $record instanceof Transfer => $record->transfer_date->toDateString(),
            $record instanceof Disposal => $record->disposal_date->toDateString(),
            default => now()->toDateString(),
        };
    }
}
