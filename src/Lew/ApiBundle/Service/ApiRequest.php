<?php
/**
 * Created by PhpStorm.
 * User: Lew
 * Date: 01/08/2017
 * Time: 10:02
 */

namespace Lew\ApiBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class ApiRequest
{
    private $container;
    private $key;
    private $lang;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->key = $this->container->getParameter('api_key');
        $this->lang = $this->container->getParameter('lew_api')['language'];
    }

    public function checkMovie($movie)
    {
        if ($movie === null) {
            return false;
        }
        return true;
    }

    public function searchMovie($movie, $page = 1)
    {
        $movie = urlencode($movie);
        $url = 'https://api.themoviedb.org/3/search/movie?api_key='.$this->key.'&language='.$this->lang.'&page='.$page.'&query='.$movie;
        return json_decode(file_get_contents($url));
    }

    public function getMovie($id)
    {
        $url = 'https://api.themoviedb.org/3/movie/'.$id.'?api_key='.$this->key.'&language='.$this->lang;
        return json_decode(file_get_contents($url));
    }

    public function getCredits($id)
    {
        $url = 'https://api.themoviedb.org/3/movie/'.$id.'/credits?api_key='.$this->key;
        return json_decode(file_get_contents($url));
    }

    public function getPerson($id)
    {
        $url = 'https://api.themoviedb.org/3/person/'.$id.'?api_key='.$this->key.'&language='.$this->lang;
        return json_decode(file_get_contents($url));
    }

    public function setListGenre()
    {
        $url = 'https://api.themoviedb.org/3/genre/movie/list?api_key='.$this->key.'&language='.$this->lang;
        return json_decode(file_get_contents($url));
    }

    public function getPays()
    {
        $url = 'https://restcountries.eu/rest/v2/all';
        return json_decode(file_get_contents($url));
    }

    public function getUpdatePerson()
    {
        $em = $this->container->get('doctrine.orm.default_entity_manager');
        $yesterday = new \DateTime('yesterday');
        $yesterday = $yesterday->format('Y-m-d');
        $now = new \DateTime('now');
        $now = $now->format('Y-m-d');

        $url = 'https://api.themoviedb.org/3/person/changes?api_key='.$this->key.'&end_date='.$now.'&start_date='.$yesterday.'&page=1';
        $countPage = json_decode(file_get_contents($url));

        for ($i = 1; $i < $countPage->total_pages; $i++){
            $urlAllChanges = 'https://api.themoviedb.org/3/person/changes?api_key='.$this->key.'&end_date='.$now.'&start_date='.$yesterday.'&page='.$i;
            $allChanges = json_decode(file_get_contents($urlAllChanges));
            foreach ($allChanges->results as $change) {
                $person = $this->container->get('doctrine.orm.default_entity_manager')->getRepository('LewApiBundle:Person')->find($change->id);
                if ($person != null){
                    $urlPersonChange = 'https://api.themoviedb.org/3/person/'.$change->id.'/changes?api_key='.$this->key.'&end_date='.$now.'&start_date='.$yesterday;
                    $personChanges = json_decode(file_get_contents($urlPersonChange));
                    foreach ($personChanges->changes as $personChange) {
                        if ($personChange->key == 'birthday'){
                            if (isset($personChange->items[0]) && isset($personChange->items[0]->value)){
                                if ($personChange->items[0]->action == 'updated' || $personChange->items[0]->action == 'added'){
                                    $person->setBirthDate($personChange->items[0]->value);
                                }
                            }elseif (isset($personChange->items[1]) && isset($personChange->items[1]->value)){
                                if ($personChange->items[1]->action == 'updated' || $personChange->items[1]->action == 'added'){
                                    $person->setBirthDate($personChange->items[1]->value);
                                }
                            }
                        }
                        if ($personChange->key == 'deathday'){
                            if (isset($personChange->items[0]) && isset($personChange->items[0]->value)){
                                if ($personChange->items[0]->action == 'updated' || $personChange->items[0]->action == 'added'){
                                    $person->setDeathDate($personChange->items[0]->value);
                                }
                            }elseif (isset($personChange->items[1]) && isset($personChange->items[1]->value)){
                                if ($personChange->items[1]->action == 'updated' || $personChange->items[1]->action == 'added'){
                                    $person->setDeathDate($personChange->items[1]->value);
                                }
                            }
                        }
                        if ($personChange->key == 'gender'){
                            if (isset($personChange->items[0]) && isset($personChange->items[0]->value)){
                                if ($personChange->items[0]->action == 'updated' || $personChange->items[0]->action == 'added'){
                                    $person->setSexe($personChange->items[0]->value);
                                }
                            }elseif (isset($personChange->items[1]) && isset($personChange->items[1]->value)){
                                if ($personChange->items[1]->action == 'updated' || $personChange->items[1]->action == 'added'){
                                    $person->setSexe($personChange->items[1]->value);
                                }
                            }
                        }
                        if ($personChange->key == 'biography' && $personChange->items[0]->iso_639_1 == 'fr'){
                            if (isset($personChange->items[0]) && isset($personChange->items[0]->value)){
                                if ($personChange->items[0]->action == 'updated' || $personChange->items[0]->action == 'added'){
                                    $person->setBiographie($personChange->items[0]->value);
                                }
                            }elseif (isset($personChange->items[1]) && isset($personChange->items[1]->value)){
                                if ($personChange->items[1]->action == 'updated' || $personChange->items[1]->action == 'added'){
                                    $person->setBiographie($personChange->items[1]->value);
                                }
                            }
                        }
                        if ($personChange->key == 'place_of_birth'){
                            if (isset($personChange->items[0]) && isset($personChange->items[0]->value)){
                                if ($personChange->items[0]->action == 'updated' || $personChange->items[0]->action == 'added'){
                                    $person->setBirthPlace($personChange->items[0]->value);
                                }
                            }elseif (isset($personChange->items[1]) && isset($personChange->items[1]->value)){
                                if ($personChange->items[1]->action == 'updated' || $personChange->items[1]->action == 'added'){
                                    $person->setBirthPlace($personChange->items[1]->value);
                                }
                            }
                        }
                    }
                    $em->persist($person);
                    $em->flush();
                }
            }
        }
        return true;
    }
}