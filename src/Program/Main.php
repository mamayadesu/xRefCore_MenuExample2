<?php
declare(ticks = 1);

namespace Program;

use CliForms\Common\RowHeaderType;
use CliForms\MenuBox\Checkbox;
use CliForms\MenuBox\Events\ItemClickedEvent;
use CliForms\MenuBox\Events\KeyPressEvent;
use CliForms\MenuBox\Events\MenuBoxCloseEvent;
use CliForms\MenuBox\Events\MenuBoxOpenEvent;
use CliForms\MenuBox\Events\SelectedItemChangedEvent;
use CliForms\MenuBox\Label;
use CliForms\MenuBox\MenuBoxControl;
use CliForms\MenuBox\MenuBoxDelimiter;
use CliForms\MenuBox\Radiobutton;
use Data\String\ForegroundColors;
use IO\Console;
use CliForms\MenuBox\MenuBox;
use CliForms\MenuBox\MenuBoxItem;

class Main
{
    public function __construct(array $args)
    {
        $menu = new MenuBox("oOoOoOo My First Menu oOoOoOo", $this);
        $menu->Id = "mymenubox";
        $menu->SetRowHeaderItemDelimiter(" ");
        $menu->SetRowsHeaderType(RowHeaderType::STARS);
        $menu->SetDescription("This is an example of menu");

        /**
         * ADDING EVENTS TO OUR MENUBOX
         */
        $menu->KeyPressEvent = function(KeyPressEvent $event) : void
        {
            $event->MenuBox->SetDescription("Dude, you pressed " . $event->Key);
        };

        $menu->SelectedItemChangedEvent = function(SelectedItemChangedEvent $event) : void
        {
            /**
             * I didn't come up with an example for this event, but I believe in your imagination!
             */
        };

        $menu->OpenEvent = function(MenuBoxOpenEvent $event) : void
        {
            $event->MenuBox->ResultOutputLine("I'm opened!");
        };

        $menu->CloseEvent = function(MenuBoxCloseEvent $event) : void
        {
            $event->MenuBox->ResultOutputLine("closing");
            Console::WriteLine("i'm closing");
        };

        /**
         * CREATING ELEMENTS
         */
        $item1 = new MenuBoxItem("Hello world", "Hi!", function(ItemClickedEvent $event) : void
        {
            /**
             * Just prints some text
             */
            $this->HelloWorld($event->MenuBox);
        });

        $item2 = new Checkbox("Do you want to disable 'Check me'?", "Changes 'Check me' `Disabled` property", function(ItemClickedEvent $event) : void
        {
            /** @var Checkbox $item */$item = $event->Item;
            $event->MenuBox->GetElementById("checkme")->Disabled($item->Checked());
        });
        $item2->Id = "item2";

        $item3 = new MenuBoxItem("Open child menu box", "??", function(ItemClickedEvent $event) : void
        {
            $this->ChildMenuBox();
        });

        /**
         * This item is unselectable
         */
        $item4 = new MenuBoxItem("I'm so sorry, but you can't select me :(", "", function(ItemClickedEvent $event) : void{});
        $item4->Selectable(false);

        $item5 = new Checkbox("Check me!", "hint4", function(ItemClickedEvent $event) : void
        {
            /**
             * Output depends on "Checked" property
             */

            /** @var Checkbox $item */$item = $event->Item;
            if ($item->Checked())
            {
                $event->MenuBox->ResultOutputLine("Yay!", ForegroundColors::GREEN);
            }
            else
            {
                $event->MenuBox->ResultOutputLine("Hey, give my cross back!", ForegroundColors::YELLOW);
            }
        });
        $item5->Id = "checkme";

        $item6 = new MenuBoxItem("Revert items", "hint5", function(ItemClickedEvent $event) : void
        {
            /**
             * This item reverts ALL elements of MenuBox. More precisely, it changes item's sort ordering.
             */
            $menu = $event->MenuBox;
            $k = count($menu->GetSortedItems());
            foreach ($menu->GetSortedItems() as $item)
            {if(!$item instanceof MenuBoxControl)continue;
                $item->Ordering($k);
                $k--;
            }
            $menu->ResultOutputLine("Now your items are upside down!");
        });

        /**
         * Invisible item
         */
        $item7 = new MenuBoxItem("I'M INVISIBLE!!", "", function(ItemClickedEvent $event) : void{});
        $item7->Visible(false);

        /**
         * The first radiobuttons group
         */
        ($rb1 = new Radiobutton("Rb1", "hint6"))->GroupName("firstgroup");
        ($rb2 = new Radiobutton("Radiobutton 2", "hint7"))->GroupName("firstgroup");
        ($rb3 = new Radiobutton("R b 3", "hint8"))->GroupName("firstgroup");

        /**
         * The second radiobuttons group
         */
        ($rb4 = new Radiobutton("Radio button 4", "hint9"))->GroupName("second");
        ($rb5 = new Radiobutton("Radio button 5", "hint10"))->GroupName("second");

        $zero = new MenuBoxItem("Close Menu", "hint11", function(ItemClickedEvent $event)
        {
            /**
             * This item closes MenuBox
             */
            $event->MenuBox->Close();
        });

        /**
         * Now adding all items...
         */
        $menu->
            AddItem($item1)->
            AddItem($item2)->
            AddItem($item3)->
            AddItem($item4)->
            AddItem($item5)->
            AddItem($item6)->
            AddItem($item7)->
            AddItem(new Label(""))->
            AddItem(new Label("Radio buttons group"))->
            AddItem($rb1)->
            AddItem($rb2)->
            AddItem($rb3)->
            AddItem(new MenuBoxDelimiter)->
            AddItem($rb4)->
            AddItem($rb5)->
            SetZeroItem($zero);

        $menu->ItemsContainerHeight(12);

        /**
         * ...and just render it
         */
        $menu->Render();

        /**
         * Attention! The code below WON'T be executed until MenuBox running
         */
        sleep(1);
        Console::WriteLine("Press ENTER to exit");
        Console::ReadLine();
    }
    
    public function HelloWorld(MenuBox $menu) : void
    {
        $menu->ResultOutputLine("Oh, hello world!");
    }
    
    public function AnyIdeaToMethodName(MenuBox $menu) : void
    {
        $menu->ResultOutputLine("s a m p l e t e x t");
    }

    public function ChildMenuBox() : void
    {
        /**
         * Testing menu box inside menu box!
         */
        $menu = new MenuBox("Child menu box", $this);
        $menu->SetDescription("hey!");
        /** @var array<MenuBoxItem> $items */$items = [];
        /** @var MenuBoxItem $item */$item = null;
        $hide = 0;
        for ($i = 1; $i <= 30; $i++)
        {
            $items[] = $item = new MenuBoxItem("Item " . $i, "Hint #" . $i, function(ItemClickedEvent $event) : void {});
            if ($hide > 0)
            {
                $item->Visible(false);

                // hide every second element
                $hide++;
                if ($hide == 2)
                {
                    $hide = 0;
                }
            }
            else
            {
                $hide++;
            }
        }

        $menu->
            AddItem((new MenuBoxItem("Print me time", "", function(ItemClickedEvent $event) : void
            {
                $event->MenuBox->ResultOutputLine("Now " . date("d.m.Y H:i:s", time()));
            })))->
            SetZeroItem((new MenuBoxItem("Close", "", function(ItemClickedEvent $event) : void
            {
                $event->MenuBox->Close();
            })));

        foreach ($items as $item)
        {
            $menu->AddItem($item);
        }
        $menu->ItemsContainerHeight(7);
        $menu->Id = "child";

        $menu->Render();
        $menu->Dispose();
    }
}