<x-app-layout>
    <div class="content-box main-background d-flex flex-column min-vh-100">
        <div class="container mb-5">
            <div>
                @include('components.branding')
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <a type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close"><i
                                class="fa-solid fa-xmark"></i></a>
                        <div class="info-icon mb-3">
                            <img src="{{ asset('files/main/info.png') }}" alt="" />
                        </div>
                        <p class="modal-main-text mb-1">Do you want to reschedule your visit ?</p>
                        <p class="warning-text text-center px-5">Note: You may reschedule your selected date
                            <strong>only once</strong>.</p>
                        <div class="">
                            <button id="confirmVisitButton" type="submit" class="button button-primary w-100 mb-2">
                                YES
                            </button>
                            <button id="cancelModalButton" type="button" class="button button-secondary w-100 mb-2"
                                data-bs-dismiss="modal">
                                NO
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-container p-4 mt-auto">
            @include('components.footer')
        </div>
    </div>
</x-app-layout>
