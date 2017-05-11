<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomPostgresConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('CREATE TEXT SEARCH DICTIONARY english_stem_nostop (Template = snowball, Language = english);');
        DB::statement('CREATE TEXT SEARCH CONFIGURATION public.english_nostop ( COPY = pg_catalog.english );');
        DB::statement('ALTER TEXT SEARCH CONFIGURATION public.english_nostop ALTER MAPPING FOR asciiword, asciihword, hword_asciipart, hword, hword_part, word WITH english_stem_nostop;');
        DB::statement("ALTER DATABASE \"".env('DB_DATABASE')."\" SET default_text_search_config = 'public.english_nostop'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP TEXT SEARCH CONFIGURATION public.english_nostop');
        DB::statement('DROP TEXT SEARCH DICTIONARY english_stem_nostop');
        DB::statement("ALTER DATABASE \"".env('DB_DATABASE')."\" SET default_text_search_config = 'pg_catalog.english'");
    }
}
