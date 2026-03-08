<?php

namespace Tests\Unit;

use App\Models\Approval;
use App\Models\Disposal;
use App\Models\PropertyTransaction;
use App\Models\Transfer;
use Tests\TestCase;

class ApprovalTest extends TestCase
{
    public function test_it_builds_the_view_url_for_an_issuance_approval(): void
    {
        $approval = new Approval(['status' => 'pending']);
        $approval->approvable_type = PropertyTransaction::class;
        $approval->setRelation('approvable', (new PropertyTransaction())->forceFill(['id' => 11, 'control_no' => 'ICS-001']));

        $this->assertSame('Issuance', $approval->approvableLabel());
        $this->assertSame(route('issuance.show', ['issuance' => 11]), $approval->approvableViewUrl());
    }

    public function test_it_builds_the_view_url_for_a_transfer_approval(): void
    {
        $approval = new Approval(['status' => 'pending']);
        $approval->approvable_type = Transfer::class;
        $approval->setRelation('approvable', (new Transfer())->forceFill(['id' => 7, 'control_no' => 'PTR-007']));

        $this->assertSame('Transfer', $approval->approvableLabel());
        $this->assertSame(route('transfer.show', ['transfer' => 7]), $approval->approvableViewUrl());
    }

    public function test_it_builds_the_view_url_for_a_disposal_approval(): void
    {
        $approval = new Approval(['status' => 'pending']);
        $approval->approvable_type = Disposal::class;
        $approval->setRelation('approvable', (new Disposal())->forceFill(['id' => 5, 'control_no' => 'IIRUP-005']));

        $this->assertSame('Disposal', $approval->approvableLabel());
        $this->assertSame(route('disposal.show', ['disposal' => 5]), $approval->approvableViewUrl());
    }
}
