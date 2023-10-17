<?php

class User{
    public $name;
    public $money;
    public function __construct($name,$money)
    {
        $this->name = $name;
        $this->money = $money;
    }
    public function point(User $user)
    {
        $this->money++;
        $user->money--;
    }
    public function nomoney()
    {
        return $this->money == 0;
    }
    public function bank()
    {
        return $this->money;
    }
    public function chance(User $user)
    {
        return round($this->bank()/($this->bank()+$user->bank()), 2) * 100 . '%';

    }
}
class Game{
    protected $user1;
    protected $user2;
    protected $flips = 1;
    public function __construct(User $user1,User $user2)
    {
        $this->user1 = $user1;
        $this->user2 = $user2;
    }
    public function flip()
    {
        return rand(0,1) ? "орел" : "решка";
    }
    public function start()
    {

        echo <<<EOT
        {$this->user1->name}: Шансы на победу {$this->user1->chance($this->user2)}
        {$this->user2->name}: Шансы на победу {$this->user2->chance($this->user1)}
        
        EOT;
        $this->play();
    }
    public function play()
    {
        //Бросаем монету
        //Если орел, user1 получает 1 купюру, user2 теряет 1 купюру
        //Если решка, user2 получает 1 купюру, user1 теряет 1 купюру
        while (true){
            if($this->flip() == "орел"){
                $this->user1->point($this->user2);
            }
            else{
                $this->user2->point($this->user1);
            }
            if($this->user1->nomoney()|| $this->user2->nomoney()){
                return $this->end();
            }
            $this->flips++;
            //Если у кого-то деньги кончаются(т.е равны 0), то игра завершается.
        }

    }
    public function wins(): User //Указываем на возвращаемый тип данных. В данном случае функция возвращает объект класса "User".
    {
        return $this->user1->bank() > $this->user2->bank() ? $this->user1 : $this->user2;
    }
    public function end(){
        //Побеждает тот, у кого больше монет(>0)
        echo "Кол-во монет у " . $this->user1->name .": " . $this->user1->bank() ."\n";
        echo "Кол-во монет у " . $this->user2->name .": " . $this->user2->bank() ."\n";
        echo "\nИгра закончилась \n".
            "Победитель: " . $this->wins()->name .
            "\nБросков в общем: " . $this->flips;
    }
}


$games = new Game(
    new User("User_1", 100),
    new User("User_2",100)
);

$games->start();
