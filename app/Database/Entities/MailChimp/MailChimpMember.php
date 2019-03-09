<?php
declare(strict_types=1);

namespace App\Database\Entities\MailChimp;

use Doctrine\ORM\Mapping as ORM;
use EoneoPay\Utils\Str;

/**
 * @ORM\Entity()
 */
class MailChimpMember extends MailChimpEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    private $memberId;

    /**
     * @ORM\Column(name="email_address", type="string")
     *
     * @var string
     */
    private $emailAddress;

    /**
     * @ORM\Column(name="status", type="string")
     *
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(name="mailchimp_id", type="string", nullable=true)
     *
     * @var string
     */
    private $mailChimpId;

    /**
     * @ORM\Column(name="list_id", type="string", nullable=true)
     *
     * @var string
     */
    private $listId;

    /**
     * @return string
     */
    public function getMemberId(): string
    {
        return $this->memberId;
    }

    /**
     * @param string $memberId
     */
    public function setMemberId(string $memberId): void
    {
        $this->memberId = $memberId;
    }

    /**
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * @param string $listId
     */
    public function setListId(string $listId): void
    {
        $this->listId = $listId;
    }


    /**
     * @param string $emailAddress
     *
     * @return MailChimpMember
     */
    public function setEmailAddress(string $emailAddress): MailChimpMember
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * @param string $status
     *
     * @return MailChimpMember
     */
    public function setStatus(string $status): MailChimpMember
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Set mailchimp id of the list.
     *
     * @param string $mailChimpId
     *
     * @return \App\Database\Entities\MailChimp\MailChimpMember
     */
    public function setMailChimpId(string $mailChimpId): MailChimpMember
    {
        $this->mailChimpId = $mailChimpId;

        return $this;
    }

    /**
     * Get id.
     *
     * @return null|string
     */
    public function getId(): ?string
    {
        return $this->memberId;
    }

    /**
     * Get mailchimp id of the list.
     *
     * @return null|string
     */
    public function getMailChimpId(): ?string
    {
        return $this->mailChimpId;
    }

    /**
     * Get validation rules for mailchimp entity.
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'email_address' => 'required|string',
            'status' => 'required|string|in:subscribed,unsubscribed,cleaned,pending',
            'mail_chimp_id' => 'nullable|string',
            'list_id' => 'nullable|string',
        ];
    }

    /**
     * Get array representation of entity.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $str = new Str();

        foreach (\get_object_vars($this) as $property => $value) {
            $array[$str->snake($property)] = $value;
        }

        return $array;
    }

}
