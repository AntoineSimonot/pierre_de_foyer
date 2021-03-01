<?php
    class Card{
        public $name;
        public $life;
        public $damage;
        function __construct(string $name, int $life, int $damage) {
            $this->name = $name;
            $this->life = $life;
            $this->damage = $damage;
        }
    }

    class Deck{
        public $cards;

        public function draw(array & $deck ){
            $deck = array_values($deck);
            $sortedDeck = array_values($deck);
            $rand = mt_rand(0,count($sortedDeck) -1 );
            $drawnCard = $sortedDeck[$rand];
            $data = [$drawnCard, $rand];
            unset($deck[$rand]);
            return $data;
        }

        public function shuffle_array(array $array) {
            $shuffled_array = [];
            $arr_length = sizeof($array);
        
            if($arr_length < 2) {
                return $array;
            }
        
            while($arr_length) {
                --$arr_length;
                $rand_key = array_keys($array)[mt_rand(0, $arr_length)];
                $shuffled_array[$rand_key] = $array[$rand_key];
                unset($array[$rand_key]);
            }
        
            return $shuffled_array;
        }
        
    

        function __construct() {
            $names = ["loup", "troll", "gobelin"];
            $a = 0;
            $this->cards = [];
            while ($a <= 29) {
                array_push($this->cards, new Card($names[rand(0, count($names) -1 )], rand(5, 15), rand(5, 15)));
                $a++;
            }
            
        } 
    }

    class Player{
        public $name;
        public $health;
        public $card;

        function __construct(string $name, int $health, object $card) {
            $this->name = $name;
            $this->health = $health;
            $this->card = $card;
        } 
    }

    $deck1 = new Deck();
    $deck2 = new Deck();

    $finalDeck1 = $deck1->shuffle_array($deck1->cards) ;
    $finalDeck2 = $deck2->shuffle_array($deck2->cards) ;


    if (empty($finalDeck1)) {
        foreach ($deck1 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                array_push($finalDeck1, $value1);
            }
        }
    }


    if (empty($finalDeck2)) {
        foreach ($deck2 as $key => $value) {
            foreach ($value as $key1 => $value1) {
                array_push($finalDeck2, $value1);
            }
        }
    }

    $player1 = new Player("player1", 30 , $deck1->draw($finalDeck1)[0]);
    $player2 = new Player("player2", 30 , $deck2->draw($finalDeck2)[0]);

    $tour = 0;

    function combatText($player1DMG, $player2DMG, $player1Name, $player2Name, $player1Life, $player2Life) {
        echo "La carte du player 1 " .$player1Name . " à " . $player1DMG. " de dégats et " . $player1Life . " pv". "<br>";
        echo "La carte du player 2 " .$player2Name . " à "  . $player2DMG . " de dégats et " . $player2Life . " pv". "<br>" . "<br>" ; 
    }
    function combatPV($player1Health, $player2Health){
        echo 'PDV du player 1 = ' . $player1Health . "<br>";
        echo 'PDV du player 2 = ' . $player2Health . "<br>" . "<br>"; 
    }

    while ($player1->health > 0 && $player2->health > 0 && count($finalDeck1) > 0 && count($finalDeck2) > 0) {
        $tour++;
        echo "Tour " .  $tour . "<br>" ; "<br>";
        $player1->card = $deck1->draw($finalDeck1)[0];
        $player2->card = $deck2->draw($finalDeck2)[0];
        
        if ($player1->card->life < $player2->card->damage && $player2->card->life > $player1->card->damage) {
            combatText($player1->card->damage, $player2->card->damage, $player1->card->name, $player2->card->name, $player1->card->life, $player2->card->life);
            $player1->health = $player1->health + ($player1->card->life - $player2->card->damage);
            combatPV($player1->health, $player2->health);
        }
        elseif ($player2->card->life < $player1->card->damage && $player1->card->life > $player2->card->damage){
            combatText($player1->card->damage, $player2->card->damage, $player1->card->name, $player2->card->name, $player1->card->life, $player2->card->life);
            $player2->health = $player2->health + ($player2->card->life - $player1->card->damage);
            combatPV($player1->health, $player2->health);
        }
        elseif ($player2->card->life < $player1->card->damage && $player1->card->life < $player2->card->damage){
            combatText($player1->card->damage, $player2->card->damage, $player1->card->name, $player2->card->name, $player1->card->life, $player2->card->life);
            $player2->health = $player2->health + ($player2->card->life - $player1->card->damage);
            $player1->health = $player1->health + ($player1->card->life - $player2->card->damage);
            combatPV($player1->health, $player2->health);
        }
        elseif ($player2->card->life > $player1->card->damage && $player1->card->life > $player2->card->damage) {
            combatText($player1->card->damage, $player2->card->damage, $player1->card->name, $player2->card->name, $player1->card->life, $player2->card->life);
            combatPV($player1->health, $player2->health);
        }
        else{
            combatText($player1->card->damage, $player2->card->damage, $player1->card->name, $player2->card->name, $player1->card->life, $player2->card->life);
            combatPV($player1->health, $player2->health);
        }
}
    

    if ($player1->health < 1 && $player2->health < 1) {
        echo "Egalité";
    }
    elseif ($player1->health < 1) {
        echo 'Le player 2 à gagné';
    }
    elseif ($player2->health < 1) {
        echo 'Le player 1 à gagné';
    }

    if (empty($finalDeck1)  && empty($finalDeck2)){
        if ($player1->health < $player2->health) {
        echo 'Les decks sont vides, Le player 2 à gagné';
        }
        if ($player2->health < $player1->health) {
        echo 'Les decks sont vides, Le player 1 à gagné';
        }
        if ($player1->health == $player2->health) {
        echo 'Les decks sont vides, égalité';
        }
    }
    
?>

