<?php

namespace Maksatsaparbekov\KuleshovAuth\Notifications;

class FirebasePush
{
    private $title;
    private $body;
    private $data = [];
    private $registration_ids; // Device token or topic

    /**
     * Sets the notification title.
     *
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Sets the notification body.
     *
     * @param string $body
     * @return $this
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Sets the data for the notification.
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sets the recipient of the notification.
     *
     * @param array $registration_ids Device token, topic, or condition.
     * @return $this
     */
    public function setTo(array $registration_ids): self
    {
        $this->registration_ids = $registration_ids;
        return $this;
    }

    /**
     * Prepares the payload for sending the notification.
     *
     * @return array The notification payload.
     */
    public function getPayload(): array
    {
        return [
            'registration_ids' => $this->registration_ids,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
            ],
            'data' => $this->data,
        ];
    }
}
