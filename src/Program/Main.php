<?php

namespace Program;

use IO\Console;
use CliForms\MenuBox\MenuBox;
use CliForms\MenuBox\MenuBoxItem;
use CliForms\MenuBox\MenuBoxTypes;
use Data\String\BackgroundColors;
use Data\String\ForegroundColors;

class Main
{
    public function __construct(array $args)
    {
        $menu = new MenuBox("oOoOoOo My First Menu oOoOoOo", $this, MenuBoxTypes::KeyPressType);
        $menu->SetDescription("This is an example of menu. Move between elements using keyboard arrows and press ENTER to selected element.");
        $menu->
            AddItem((new MenuBoxItem("Hello world", function(MenuBox $menu)
            {
                $this->HelloWorld($menu);
            })))->
            
            AddItem((new MenuBoxItem("foo bar", function(MenuBox $menu)
            {
                $this->foobar($menu);
            })))->
            
            AddItem((new MenuBoxItem("Third item", function(MenuBox $menu)
            {
                $this->AnyIdeaToMethodName($menu);
            })))->
            
            SetZeroItem((new MenuBoxItem("Close Menu", function(MenuBox $menu)
            {
                $menu->Close();
            })));
            
        $menu->Render();
        
        /*
         * Or you can use next variant:
         * 
         * do
         * {
         *     ($menu->Render2())($menu);
         * }
         * while (!$menu->IsClosed());
         */
        
        sleep(1);
        Console::WriteLine("Press ENTER to exit");
        Console::ReadLine();
    }
    
    public function HelloWorld(MenuBox $menu) : void
    {
        $menu->ResultOutputLine("Oh, hello world!");
    }
    
    public function foobar(MenuBox $menu) : void
    {
        $menu->ResultOutputLine("Foo Bar");
    }
    
    public function AnyIdeaToMethodName(MenuBox $menu) : void
    {
        $menu->ResultOutputLine("s a m p l e t e x t");
    }
}