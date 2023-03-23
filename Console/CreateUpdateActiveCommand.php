<?php

namespace Modules\ShopDiscounts\Console;

use Modules\ShopDiscounts\Entities\Discount;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateUpdateActiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'shopdiscounts:update_active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks active discounts and if the valid until date is reached, the discount is set ot inactive.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $discounts = Discount::where('active', true)->get();
        foreach($discounts as $discount){
            $discount->updateActive();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
