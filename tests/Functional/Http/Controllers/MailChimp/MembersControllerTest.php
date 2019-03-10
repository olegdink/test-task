<?php
declare(strict_types=1);


use App\Database\Entities\MailChimp\MailChimpList;
use App\Database\Entities\MailChimp\MailChimpMember;
use Tests\App\TestCases\WithDatabaseTestCase;


class MembersControllerTest extends WithDatabaseTestCase
{

    public function testCRUDMemberSuccessfully(): void
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
        $content = \json_decode($this->response->getContent(), true);
        $this->assertResponseOk();
        self::assertArrayHasKey('mail_chimp_id', $content);
        self::assertNotNull($content['mail_chimp_id']);


        $lists = $this->entityManager->getRepository(MailChimpList::class)->findAll();

        // Create Member

        $this->post('/mailchimp/lists/'.$lists[0]->getId().'/members', [
            "email_address" => $faker->email,
        	"status" => "subscribed"
        ]);
        $content = \json_decode($this->response->getContent(), true);
        $this->assertResponseOk();
        self::assertArrayHasKey('mail_chimp_id', $content);
        self::assertNotNull($content['mail_chimp_id']);


        $members = $this->entityManager->getRepository(MailChimpMember::class)->findAll();

        // Update Member

        $this->put('/mailchimp/lists/'.$lists[0]->getId().'/members/'.$members[0]->getId(), [
            "email_address" => $faker->email,
            "status" => "subscribed"
        ]);
        $content = \json_decode($this->response->getContent(), true);
        $this->assertResponseOk();
        self::assertArrayHasKey('mail_chimp_id', $content);
        self::assertNotNull($content['mail_chimp_id']);
        self::assertArrayHasKey('email_address', $content);
        self::assertArrayHasKey('list_id', $content);
        self::assertArrayHasKey('member_id', $content);


        $members = $this->entityManager->getRepository(MailChimpMember::class)->findAll();

        // Delete Member

        $this->delete('/mailchimp/lists/'.$lists[0]->getId().'/members/'.$members[0]->getId());
        $this->response->content();
        $this->assertResponseOk();

        // Delete list

        $this->delete('/mailchimp/lists/'.$lists[0]->getId());
        $this->response->content();
        $this->assertResponseOk();

    }


}
