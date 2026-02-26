<li class="nav-item dropdown dropdown-notification me-25">
    <a class="nav-link" href="#" data-bs-toggle="dropdown">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="14"
            height="14"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
            class="feather feather-bell ficon"
        >
            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
        </svg>
        <span class="badge rounded-pill bg-danger badge-up">5</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end">
        <li class="dropdown-menu-header">
            <div class="dropdown-header d-flex">
                <h4 class="notification-title mb-0 me-auto">Сповіщення</h4>
                <div class="badge rounded-pill badge-light-primary">6 Нових</div>
            </div>
        </li>
        <li class="scrollable-container media-list ps">
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar">
                            <img
                                src="{{ asset('images/portrait/small/avatar-s-15.jpg') }}"
                                alt="avatar"
                                width="32"
                                height="32"
                            />
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">Congratulation Sam 🎉</span>
                            winner!
                        </p>
                        <small class="notification-text">Won the monthly best seller badge.</small>
                    </div>
                </div>
            </a>
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar">
                            <img
                                src="{{ asset('images/portrait/small/avatar-s-3.jpg') }}"
                                alt="avatar"
                                width="32"
                                height="32"
                            />
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">New message</span>
                            &nbsp;received
                        </p>
                        <small class="notification-text">You have 10 unread messages</small>
                    </div>
                </div>
            </a>
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar bg-light-danger">
                            <div class="avatar-content">MD</div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">Revised Order 👋</span>
                            &nbsp;checkout
                        </p>
                        <small class="notification-text">MD Inc. order updated</small>
                    </div>
                </div>
            </a>
            <div class="list-item d-flex align-items-center">
                <h6 class="fw-bolder me-auto mb-0">Системні сповіщення</h6>
                <div class="form-check form-check-primary form-switch">
                    <input
                        class="form-check-input"
                        id="systemNotification"
                        type="checkbox"
                        checked=""
                    />
                    <label class="form-check-label" for="systemNotification"></label>
                </div>
            </div>
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar bg-light-danger">
                            <div class="avatar-content">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-x avatar-icon"
                                >
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">Server down</span>
                            &nbsp;registered
                        </p>
                        <small class="notification-text">
                            USA Server is down due to height CPU usage
                        </small>
                    </div>
                </div>
            </a>
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar bg-light-success">
                            <div class="avatar-content">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-check avatar-icon"
                                >
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">Sales report</span>
                            &nbsp;generated
                        </p>
                        <small class="notification-text">Last month sales report generated</small>
                    </div>
                </div>
            </a>
            <a class="d-flex" href="#">
                <div class="list-item d-flex align-items-start">
                    <div class="me-1">
                        <div class="avatar bg-light-warning">
                            <div class="avatar-content">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="14"
                                    height="14"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="feather feather-alert-triangle avatar-icon"
                                >
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"
                                    ></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="list-item-body flex-grow-1">
                        <p class="media-heading">
                            <span class="fw-bolder">High memory</span>
                            &nbsp;usage
                        </p>
                        <small class="notification-text">BLR Server using high memory</small>
                    </div>
                </div>
            </a>
            <div class="ps__rail-x" style="left: 0; bottom: 0">
                <div class="ps__thumb-x" tabindex="0" style="left: 0; width: 0"></div>
            </div>
            <div class="ps__rail-y" style="top: 0; right: 0">
                <div class="ps__thumb-y" tabindex="0" style="top: 0; height: 0"></div>
            </div>
        </li>
        <li class="dropdown-menu-footer">
            <a class="btn btn-primary w-100 waves-effect waves-float waves-light" href="#">
                Прочитати всі сповіщення
            </a>
        </li>
    </ul>
</li>
