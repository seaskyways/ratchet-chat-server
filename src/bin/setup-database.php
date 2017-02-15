<?php
/**
 * Created by IntelliJ IDEA.
 * User: User1
 * Date: 15/02 - Feb/17
 * Time: 10:24 AM
 */
ORM::configure('mysql:host=localhost;dbname=chat_db');
ORM::configure('username', 'root');
ORM::configure('id_column', 'id');
ORM::configure('logging', true);
//Model::$auto_prefix_models = '\\MyApp\\Database\\';
Model::$short_table_names = true;