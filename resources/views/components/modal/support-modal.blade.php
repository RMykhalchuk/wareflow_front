<div
    class="modal fade"
    id="{{ $modalId }}"
    tabindex="-1"
    aria-labelledby="{{ $modalId }}Title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <form
                    id="feedback-form"
                    class="auth-login-form w-100 h-100 d-flex flex-column px-2"
                >
                    <h3 class="mb-1">
                        {{ __($titleKey) }}
                    </h3>
                    <p class="mb-1">
                        {{ __($subtitleKey) }}
                    </p>
                    <div class="mb-2 input-email-group-modal">
                        <label class="form-label" for="feedBackEmailInp">
                            {{ __($emailLabelKey) }}
                        </label>
                        <input
                            class="form-control"
                            id="feedBackEmailInp"
                            type="email"
                            name="login"
                            placeholder="{{ __($emailPlaceholderKey) }}"
                            aria-describedby="feedBackEmailInp"
                            autofocus=""
                            tabindex="1"
                            style="margin-bottom: 5px"
                        />
                        <a
                            href="#"
                            class="text-secondary text-decoration-underline link-to-numberInputModal"
                        >
                            {{ __($emailLinkKey) }}
                        </a>
                    </div>

                    <div
                        class="input-number-group-modal inpSelectNumCountry"
                        style="padding-top: 2px; margin-bottom: 7px"
                    >
                        <div class="mb-1 d-flex flex-column">
                            <label class="form-label" for="feedBackNumberInp">
                                {{ __($phoneLabelKey) }}
                            </label>
                            <input
                                class="form-control input-number-country"
                                id="feedBackNumberInp"
                                name="login"
                                placeholder="{{ $phonePlaceholderKey }}"
                                aria-describedby="feedBackNumberInp"
                                autofocus=""
                            />
                            <a
                                href="#"
                                class="text-secondary text-decoration-underline link-to-emailInputModal"
                                style="margin-top: 5px"
                            >
                                {{ __($phoneLinkKey) }}
                            </a>
                        </div>
                    </div>

                    <div>
                        <p class="m-0">
                            {{ __($contactInfoKey) }}
                        </p>
                        <ul>
                            <li>
                                <p class="m-0">
                                    {{ __($phoneNumberKey) }}
                                    <a
                                        class="fw-medium-c text-secondary"
                                        href="{{ $phoneNumberHref }}"
                                    >
                                        {{ $phoneNumberDisplay }}
                                    </a>
                                </p>
                            </li>
                            <li>
                                <p class="m-0">
                                    {{ __($emailAddressKey) }}
                                    <a
                                        class="fw-medium-c text-secondary"
                                        href="{{ $emailAddressHref }}"
                                    >
                                        {{ $emailAddressDisplay }}
                                    </a>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="d-flex float-end">
                            <button
                                type="button"
                                class="btn btn-link cancel-btn"
                                data-bs-dismiss="modal"
                            >
                                {{ __($cancelButtonKey) }}
                            </button>
                            <button
                                id="nextStepAuth"
                                class="btn btn-primary float-end"
                                type="submit"
                            >
                                <span class="me-50">
                                    {{ __($sendButtonKey) }}
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
