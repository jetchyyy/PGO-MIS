<?php

namespace Tests\Unit;

use App\Models\Disposal;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use App\Support\DocumentCatalog;
use Tests\TestCase;

class DocumentCatalogTest extends TestCase
{
    public function test_it_lists_core_document_titles(): void
    {
        $documents = DocumentCatalog::all();

        $this->assertSame('Property Acknowledgement Receipt', $documents['PAR']['title']);
        $this->assertSame('Inventory Custodian Slip', $documents['ICS']['title']);
        $this->assertSame('Waste Materials Report', $documents['WMR']['title']);
    }

    public function test_it_lists_templates_for_issuance_documents(): void
    {
        $issuance = new PropertyTransaction([
            'document_type' => 'ICS-SPLV',
        ]);

        $documents = collect(DocumentCatalog::templatesForIssuance($issuance))->keyBy('code');

        $this->assertSame('sticker', $documents['TAG']['template']);
        $this->assertSame('ics', $documents['ICS']['template']);
        $this->assertSame('semi_property_card', $documents['SPC']['template']);
        $this->assertSame('regspi', $documents['REGSPI']['template']);
    }

    public function test_it_lists_templates_for_transfer_and_disposal_documents(): void
    {
        $transfer = new Transfer([
            'document_type' => 'PTR',
        ]);
        $disposal = new Disposal([
            'document_type' => 'RRSEP',
        ]);

        $transferDocuments = collect(DocumentCatalog::templatesForTransfer($transfer))->keyBy('code');
        $disposalDocuments = collect(DocumentCatalog::templatesForDisposal($disposal))->keyBy('code');

        $this->assertSame('ptr', $transferDocuments['PTR']['template']);
        $this->assertSame('sticker', $transferDocuments['TAG']['template']);
        $this->assertSame('rrsep', $disposalDocuments['RRSEP']['template']);
        $this->assertSame('wmr', $disposalDocuments['WMR']['template']);
        $this->assertSame('iirusp', $disposalDocuments['IIRUSP']['template']);
    }
}
