<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * STEP 1
         * Netralisasi PDID duplikat (TANPA TRANSACTION)
         */
        $duplicates = DB::table('p_i_p_s')
            ->select('pdid')
            ->whereNotNull('pdid')
            ->groupBy('pdid')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('pdid');

        foreach ($duplicates as $pdid) {
            $rows = DB::table('p_i_p_s')
                ->where('pdid', $pdid)
                ->orderBy('id')
                ->get();

            foreach ($rows->skip(1) as $row) {
                DB::table('p_i_p_s')
                    ->where('id', $row->id)
                    ->update([
                        'pdid' => $row->pdid . '__DUP__' . $row->id,
                    ]);
            }
        }

        /**
         * STEP 2
         * Tambahkan UNIQUE ke PDID (AMAN)
         */
        Schema::table('p_i_p_s', function (Blueprint $table) {
            $table->unique('pdid', 'pips_pdid_unique');
        });
    }

    public function down(): void
    {
        Schema::table('p_i_p_s', function (Blueprint $table) {
            $table->dropUnique('pips_pdid_unique');
        });
    }
};
