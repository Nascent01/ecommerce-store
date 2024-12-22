<?php

namespace App\Console\Commands\Products;

use Illuminate\Console\Command;
use App\Traits\CommandTrait;

class ImportProducts extends Command
{
    use CommandTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products data from Json file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scriptTimeStart = $this->displayCommandStart('Importing products data has started...');
        $this->truncateTables(['products', 'product_translations', 'product_categories', 'product_category_translations']);

        // Import products data from Json file

        $this->displayExecutionTime($scriptTimeStart);
    }
}
