<?php

namespace Able;

/**
 * This file is part of Able,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @package Able
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Able\Traits\AbleUserTrait;
use Traitor\Traitor;

class AddAbleUserTraitUseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'able:add-trait';

    /**
     * Trait added to User model
     *
     * @var string
     */
    protected $targetTrait = AbleUserTrait::class;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $userModel = $this->getUserModel();
        
        if (! class_exists($userModel)) {
            $this->error("Class $userModel does not exist.");
            return;
        }

        if ($this->alreadyUsesAbleUserTrait()) {
            $this->error("Class $userModel already uses AbleUserTrait.");
            return;
        }

        Traitor::addTrait($this->targetTrait)->toClass($userModel);

        $this->info("AbleUserTrait added successfully");
    }

    /**
     * @return bool
     */
    protected function alreadyUsesAbleUserTrait()
    {
        return in_array(AbleUserTrait::class, class_uses($this->getUserModel()));
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Add AbleUserTrait to {$this->getUserModel()} class";
    }

    /**
     * @return string
     */
    protected function getUserModel()
    {
        return Config::get('auth.providers.users.model', 'App\User');
    }
}
