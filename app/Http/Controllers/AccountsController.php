<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accounts;

class AccountsController extends Controller
{
    public function doImport($companyId, $data)
    {
        $accountList = [];

        foreach ($data as $key => $account) {
            $accountList[$key] = [
                'qbo_id' => $account->Id,
                'account_name' => $account->FullyQualifiedName,
                'account_num' => isset($account->AcctNum) ? $account->AcctNum : "",
                'account_type' => $account->AccountType,
                'account_subtype' => $account->AccountSubType,
                'company_id' => $companyId,
                'balance' => $account->CurrentBalance,
                'is_subaccount' => isset($account->SubAccount) && $account->SubAccount == 'true' ? 1 : 0,
                'parent_ref' => isset($account->ParentRef) && $account->ParentRef > 0 ? $account->ParentRef : 0,
            ];
        }

        return $accountList;
    }

    public function save(array $list)
    {
        foreach ($list as $key => $account) {
 
            $model = \App::make(Accounts::class);
            $decide = $model->where('company_id', $account['company_id'])
                                          ->where('qbo_id', $account['qbo_id'])->first();

            if(isset($decide)){
                $decide->update($account);
            } else {

                $model->create($account);
            }
        }

        return false;
    }
}
