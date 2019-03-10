<?php
declare(strict_types=1);


use App\Database\Entities\MailChimp\MailChimpList;
use App\Database\Entities\MailChimp\MailChimpMember;
use Tests\App\TestCases\WithDatabaseTestCase;


class MembersControllerTest extends WithDatabaseTestCase
{

    /**
     * @param array $data
     *
     * @return MailChimpList
     */
    protected function createList(array $data): MailChimpList
    {
        $list = new MailChimpList($data);

        $this->entityManager->persist($list);
        $this->entityManager->flush();

        return $list;
    }

    /**
     * @param array $data
     *
     * @return MailChimpMember
     */
    protected function createMember(array $data): MailChimpMember
    {
        $member = new MailChimpMember($data);

        $this->entityManager->persist($member);
        $this->entityManager->flush();

        return $member;
    }

    public function testCreateMemberSuccessfully(): void
    {

        $faker = Faker\Factory::create();

        // Create List

        $listData = [
            'name' => $faker->name . ' list',
            'permission_reminder' => 'You signed up for updates on Greeks economy.',
            'email_type_option' => false,
            'contact' => [
                'company' => 'New member Ltd.',
                'address1' => 'Member DoeStreet 1',
                'address2' => '',
                'city' => 'Doesy',
                'state' => 'Doedoe',
                'zip' => '1672-12',
                'country' => 'US',
                'phone' => '55533344412'
            ],
            'campaign_defaults' => [
                'from_name' => 'Dink rink',
                'from_email' => 'john@doe.com',
                'subject' => 'My new campaign!',
                'language' => 'US'
            ],
            'visibility' => 'prv',
            'use_archive_bar' => false,
            'notify_on_subscribe' => 'notify@loyaltycorp.com.au',
            'notify_on_unsubscribe' => 'notify@loyaltycorp.com.au'
        ];

        $this->post('/mailchimp/lists/', $listData);
        $content = \json_decode($this->response->content(), true);
        $this->assertResponseOk();

        $lists = $this->entityManager->getRepository(MailChimpList::class)->findAll();
        var_dump($lists[0]->getId() . ' | ' . $lists[0]->getMailChimpId());

        // Create Member

        $this->post('/mailchimp/lists/'.$lists[0]->getId().'/members', [
            "email_address" => $faker->email,
        	"status" => "subscribed"
        ]);
        $content = \json_decode($this->response->content(), true);
        $this->assertResponseOk();

        $members = $this->entityManager->getRepository(MailChimpMember::class)->findAll();
        var_dump($members[0]->getId() . ' | ' . $members[0]->getMailChimpId());

        // Update Member

        $this->put('/mailchimp/lists/'.$lists[0]->getId().'/members/'.$members[0]->getId(), [
            "email_address" => $faker->email,
            "status" => "subscribed"
        ]);
        $content = \json_decode($this->response->content(), true);
        $this->assertResponseOk();

        $members = $this->entityManager->getRepository(MailChimpMember::class)->findAll();
        var_dump($members);


    }


}
