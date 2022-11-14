<?php namespace App\Models;

use App\Models\taskdb\DS\Task;

trait Model_Handles 
{

    /**
     * @return void
     * @Todo It executes model customized procedures, queries and methods
     */
    private function main(): void
    {
        Task::createQuery('Tech',function($self) {
            unset($self['[*]']);
        });
        // Write your model procedures, queries and methods here
    }
}
