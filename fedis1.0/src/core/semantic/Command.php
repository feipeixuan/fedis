<?php
/*
 * Description:
 * Author: feipeixuan
 */

namespace org\fedis\semantic;

abstract class Command
{

    abstract function exexute();
}

abstract class WriteCommand extends Command
{

}

class CreateCommand extends Command
{

    function exexute()
    {
        // TODO: Implement exexute() method.
    }
}


class InsertCommand extends WriteCommand
{
    function exexute()
    {
        // TODO: Implement exexute() method.
    }
}

class UpdateCommand extends WriteCommand
{

    function exexute()
    {
        // TODO: Implement exexute() method.
    }
}

class DeleteCommand extends WriteCommand
{

    function exexute()
    {
        // TODO: Implement exexute() method.
    }
}

class AlterCommand extends Command
{

    function exexute()
    {
        // TODO: Implement exexute() method.
    }
}