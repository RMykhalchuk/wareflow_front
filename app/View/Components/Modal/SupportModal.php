<?php

namespace App\View\Components\Modal;

use Illuminate\View\Component;

class SupportModal extends Component
{
    public string $modalId;
    public string $titleKey;
    public string $subtitleKey;
    public string $emailLabelKey;
    public string $emailPlaceholderKey;
    public string $emailLinkKey;
    public string $phoneLabelKey;
    public string $phonePlaceholderKey;
    public string $phoneLinkKey;
    public string $contactInfoKey;
    public string $phoneNumberKey;
    public string $phoneNumberHref;
    public string $phoneNumberDisplay;
    public string $emailAddressKey;
    public string $emailAddressHref;
    public string $emailAddressDisplay;
    public string $cancelButtonKey;
    public string $sendButtonKey;

    public function __construct(
        string $modalId,
        string $titleKey,
        string $subtitleKey,
        string $emailLabelKey,
        string $emailPlaceholderKey,
        string $emailLinkKey,
        string $phoneLabelKey,
        string $phonePlaceholderKey,
        string $phoneLinkKey,
        string $contactInfoKey,
        string $phoneNumberKey,
        string $phoneNumberHref,
        string $phoneNumberDisplay,
        string $emailAddressKey,
        string $emailAddressHref,
        string $emailAddressDisplay,
        string $cancelButtonKey,
        string $sendButtonKey,
    ) {
        $this->modalId = $modalId;
        $this->titleKey = $titleKey;
        $this->subtitleKey = $subtitleKey;
        $this->emailLabelKey = $emailLabelKey;
        $this->emailPlaceholderKey = $emailPlaceholderKey;
        $this->emailLinkKey = $emailLinkKey;
        $this->phoneLabelKey = $phoneLabelKey;
        $this->phonePlaceholderKey = $phonePlaceholderKey;
        $this->phoneLinkKey = $phoneLinkKey;
        $this->contactInfoKey = $contactInfoKey;
        $this->phoneNumberKey = $phoneNumberKey;
        $this->phoneNumberHref = $phoneNumberHref;
        $this->phoneNumberDisplay = $phoneNumberDisplay;
        $this->emailAddressKey = $emailAddressKey;
        $this->emailAddressHref = $emailAddressHref;
        $this->emailAddressDisplay = $emailAddressDisplay;
        $this->cancelButtonKey = $cancelButtonKey;
        $this->sendButtonKey = $sendButtonKey;
    }

    public function render()
    {
        return view('components.modal.support-modal');
    }
}
