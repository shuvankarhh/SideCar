<?php

namespace App\Xero;

use App\Models\ChartOfAccount;

class StoreChartOfAccounts
{

    // filename value
    public function store($id, $accounts)
    {
        foreach ($accounts as  $account) 
        {
            ChartOfAccount::updateOrCreate(
                [
                    'account_id' => $account['account_id'],
                    'project_api_system_id' => $id
                ],
                [
                    'project_api_system_id' => $id,
                    'account_id' => $account['account_id'],
                    'code'=> $account['code'],
                    'name'=> $account['name'],
                    'type'=> $account['type'],
                    'tax_type'=> $account['tax_type'],
                    'status'=> $account['status'],
                    'currency_code'=> $account['currency_code'] ?? ''
                ]
            );
        }

        return;
    }

}

