<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Modules\Dashboard\Repositories\TxtRepository;

class InsertTxtsRecordsModuleCompanyWinmax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        TxtRepository::new(['module' => 'CompanyWinmax', 'service' => 'TxtOrder', 'alias' => 'Pedidos - Winmax']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        TxtRepository::deleteByAlias('Pedidos - Winmax');
    }
}
