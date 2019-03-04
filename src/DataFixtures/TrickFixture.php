<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Trick;

class TrickFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 1; $i <= 10; $i++) {
            $trick = new Trick();
            $trick  ->setTitle("Trick n°$i")
                    ->setImage("http://placehold.it/400x300")
                    ->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis nunc hendrerit, pulvinar purus eget, malesuada tellus. Cras tempus, ipsum id rhoncus lacinia, metus lectus accumsan ante, a hendrerit augue eros id nunc. Morbi pretium porta quam nec dictum. Quisque euismod eu leo at venenatis. Integer hendrerit, elit quis lacinia vulputate, mauris lectus euismod enim, non auctor purus dolor vitae felis. Etiam facilisis augue diam, id commodo ante sollicitudin at. Donec ut tempus augue, vel finibus sem. Nam blandit, erat quis maximus tincidunt, eros arcu vulputate quam, nec ultricies nisl purus ut magna. Nunc id nulla elit.

                    Proin nec consectetur metus. Nunc cursus tempor sem ac fermentum. Donec sed vulputate mauris. Morbi congue malesuada tempor. Vivamus interdum quis lacus eget aliquam. Quisque vulputate vitae ante eget cursus. Donec finibus tellus vel felis dapibus, nec finibus elit suscipit. In vehicula, augue in scelerisque ornare, diam magna sagittis nibh, quis iaculis neque massa quis tellus. Praesent ut mauris tempor, aliquet quam id, vehicula dolor. Ut feugiat lacus non est gravida accumsan sit amet at nunc. Suspendisse aliquam at urna sed sagittis.
                    
                    Duis auctor ipsum et diam luctus feugiat. Aliquam efficitur nibh pellentesque ex tempus lacinia. Vestibulum felis magna, egestas non mauris vel, tempus rhoncus purus. Sed ligula elit, lacinia eu urna porta, euismod pretium dui. Fusce risus ex, sagittis ut est at, ultricies imperdiet justo. Nullam ac nisl ornare, pretium lorem nec, rhoncus arcu. Pellentesque posuere dui at ipsum condimentum commodo. Etiam ac lacus vitae tortor faucibus viverra sit amet ut lacus.
                    
                    Ut pellentesque dolor sem, venenatis pharetra tellus finibus vitae. Phasellus vitae elit placerat, aliquet sem eu, fermentum eros. Donec iaculis felis sit amet urna condimentum, id sodales enim efficitur. In hac habitasse platea dictumst. Integer enim arcu, pellentesque vitae iaculis eget, vulputate ut magna. Vivamus a elementum lectus, ut consectetur est. Curabitur sit amet nisi lacus. Vestibulum congue massa a diam vestibulum mollis. Suspendisse et mollis magna, vel consequat erat. Donec sodales diam vel mollis egestas. Aenean elit odio, consectetur et orci sit amet, cursus vestibulum urna. Quisque cursus arcu ac tortor imperdiet convallis. Vivamus vel commodo nisi.
                    
                    In hac habitasse platea dictumst. Phasellus aliquet risus ornare dolor rhoncus, lacinia dictum nibh finibus. Praesent venenatis hendrerit eleifend. Nullam sit amet mollis leo. In viverra nulla non ante iaculis ullamcorper. Nam eu aliquet leo, euismod pulvinar diam. Etiam eget libero eget massa interdum aliquam.")
                    ->setCreateAt(new \DateTime());
            
            $manager->persist($trick);
        }

        $manager->flush();
    }
}
