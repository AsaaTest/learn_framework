<?php

namespace Learn\Database\Migrations;

/**
 * The Migration interface defines the contract for database migration operations.
 */
interface Migration
{
    /**
     * Apply the database migration (e.g., create a table or make changes).
     */
    public function up();

    /**
     * Revert the database migration (e.g., drop a table or undo changes).
     */
    public function down();
}
