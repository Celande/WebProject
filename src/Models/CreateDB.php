/**
 * Creates a new database schema.

 * @param  string $schemaName The new schema name.
 * @return bool
 */
 <?php
function createSchema($schemaName)
{
    // We will use the `statement` method from the connection class so that
    // we have access to parameter binding.
    return DB::getConnection()->statement('CREATE DATABASE :schema', array('schema' => $schemaName));
}

// Now we can create a MySQL Database
//createSchema('cczadqdkmfeokkioaackazcoobffmkaz');

function createTable(){
  Artisan::call('migrate', array('database' => $databaseConnection, 'path' => 'app/database/tenants'));
}
