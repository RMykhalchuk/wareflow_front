<div class="offcanvas-end-example">
    <div
        class="offcanvas offcanvas-end"
        tabindex="-1"
        id="offcanvasEnd"
        aria-labelledby="offcanvasEndLabel"
        style="z-index: 9999; width: 400px; margin-top: 65px"
    >
        <div class="offcanvas-header d-flex justify-content-between">
            <h4 id="offcanvasEndLabel" class="fw-bolder offcanvas-title">
                {{ __('localization.bookmarks_offcanvas_title') }}
            </h4>
            <div
                class="nav-item nav-search bookmarks text-reset"
                data-bs-dismiss="offcanvas"
                aria-label="Close"
                style="list-style: none"
            >
                <a class="nav-link nav-link-grid">
                    <img
                        src="{{ asset('assets/icons/entity/bookmarks/close-button-bookmarks.svg') }}"
                        alt="Close button"
                    />
                </a>
            </div>
        </div>
        <div class="offcanvas-body px-0">
            <div class="h-100" id="body-wrapper">
                <div class="d-flex flex-column justify-content-between h-100" id="list-bookmarks">
                    <div>
                        <div class="px-2">
                            <div class="input-group input-group-merge mb-2">
                                <span class="input-group-text" id="basic-addon-search2">
                                    <i data-feather="search"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control ps-1"
                                    id="searchBar"
                                    placeholder="{{ __('localization.bookmarks_search_placeholder') }}"
                                    aria-label="{{ __('localization.bookmarks_search_placeholder') }}"
                                    aria-describedby="basic-addon-search2"
                                />
                            </div>
                        </div>
                        <div id="list-wrapper">
                            <ul id="list">
                                @foreach ($bookmarks as $bookmark)
                                    <li class="list-item">
                                        <a
                                            class="w-100"
                                            style="line-height: 32px"
                                            href="{{ URL::to('/') . $bookmark->page_uri . '?bookmark=' . $bookmark->key }}"
                                        >
                                            {{ $bookmark->name }}
                                        </a>
                                        <button class="delete-btn">
                                            <img
                                                src="{{ asset('assets/icons/entity/bookmarks/delete-button-item.svg') }}"
                                                alt="Delete Button"
                                            />
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div id="noItemsMsg" style="{{ count($bookmarks) ? 'display:none' : '' }}">
                            <h4 class="text-center mb-1 mt-2 fw-bolder">
                                {{ __('localization.bookmarks_no_items_message_title') }}
                            </h4>
                            <p class="text-center">
                                {{ __('localization.bookmarks_no_items_message_description') }}
                            </p>
                        </div>
                    </div>
                    <div class="px-2">
                        <button
                            type="button"
                            class="d-flex gap-50 align-items-center justify-content-center btn btn-primary mb-1 w-100"
                            id="create-btn"
                        >
                            <img
                                class="bookmark-icon"
                                src="{{ asset('assets/icons/entity/bookmarks/bookmark.svg') }}"
                                alt="Bookmark Icon"
                            />
                            {{ __('localization.bookmarks_create_button') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-2" id="create-bookmark">
                <input
                    type="text"
                    class="form-control"
                    id="bookmarkInput"
                    placeholder="{{ __('localization.bookmarks_create_input_placeholder') }}"
                />
                <div class="d-flex flex-row justify-content-between mt-2">
                    <button
                        class="btn btn-link cancel-btn"
                        type="button"
                        id="cancel-btn"
                        style="border: solid 1px; width: 163px"
                    >
                        {{ __('localization.bookmarks_cancel_button') }}
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="add-bookmark"
                        style="width: 163px"
                        disabled
                    >
                        {{ __('localization.bookmarks_add_button') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
