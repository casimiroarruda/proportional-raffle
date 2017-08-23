<?php
declare(strict_types=1);

namespace Duodraco\ProportionalRaffle\Transformation;

//Timestamp,Nome,Email,Telefone,Minha idade é,Gênero:,Eu sou de ...,Eu quero ir porque ...,"Minhas redes sociais são (Facebook, Twitter ou Linkedin)"

use Duodraco\ProportionalRaffle\Data\Candidate;

class CSV2Candidate
{
    protected $fileName = '';
    protected $store = [];

    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    public function __invoke()
    {
        $list = file($this->fileName);
        return $this->iterateOverList($list);
    }

    protected function iterateOverList(array $list):array
    {
        array_shift($list);
        return array_map([$this,'extractIndividualData'], $list);
    }

    protected function extractIndividualData(string $line): Candidate
    {
        $line = trim($line);
        list($timestamp, $nome, $email, $phone, $age, $gender) = explode(",", $line);
        switch ($gender){
            case 'Feminino': $gender = Candidate::GENDER_LIST[1]; break;
            case 'Masculino': $gender = Candidate::GENDER_LIST[2]; break;
            default: $gender = Candidate::GENDER_LIST[0]; break;
        }
        $candidate = new Candidate($nome, $email, $phone, $gender);
        return $candidate;
    }

}