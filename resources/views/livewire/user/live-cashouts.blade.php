@assets
<style>
    /* Live background (optional, if you want an animated background) */
    .live-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /*background: rgba(0, 0, 255, 0.1); !* Light blue for live effect *!*/
        border-radius: 10px;
        animation: pulse 2s infinite ease-in-out;
    }

    /* Animation for the signal icon (pulse effect) */
    .live-icon {
        animation: pulse 1.5s infinite ease-in-out;
    }

    /* Keyframes for pulse animation */
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.8;
        }

        50% {
            transform: scale(1.2);
            opacity: 1;
        }

        100% {
            transform: scale(1);
            opacity: 0.8;
        }
    }

    /* Optional - If you want a circular live indicator */
    .live {
        display: inline-block;
        animation: pulse 1.5s infinite ease-in-out;
        border-radius: 50%;
        background-color: #00bfff;
        /* Light blue circle */
    }
</style>

@endassets
<div {{--wire:poll.30s--}}>
    <div class="container-fluid" x-data>
        <div class="d-flex justify-content-start mt-2 small">
            <div class="card text-white me-2 py-2 px-3">
                <div class="card-body p-0 pb-0 text-center">
                    <div class="d-flex justify-content-center gap-2">
                        <div class="align-items-center ">
                            <div class="live-bg"></div>
                            <x-heroicon-s-rocket-launch class="text-primary live-icon" width="25px" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper"
                x-init="new window.swiper($el, { slidesPerView: 'auto', autoplay: { delay: 5000, disableOnInteraction: false } })">
                <div class="swiper-wrapper" wire:ignore.self>
                    @foreach($cashouts as $withdrawal)
                        <div class="swiper-slide fade-in-scale card text-white me-2 p-2"
                            style="cursor: pointer; width: auto !important;" tooltip="true" data-bs-html="true"
                            data-bs-placement="bottom"
                            title="<div class='text-start text-body'><p class='m-0'>Username: {{ $withdrawal->user->username }}</h6> <p class='m-0'>Name: {{ Auth::user()?->privacy ? splitByDashTwoParts($withdrawal->name)['first'] : $withdrawal->name }}</p> <p class='m-0'>Amount: {{ $withdrawal->amount }} Points</p> </div>">
                            <div class="card-body p-0 pb-0 text-center"
                                @click="$dispatch('activity-open', {user_id: '{{ $withdrawal->user->id }}'})">
                                <div class="d-flex justify-content-center gap-2">
                                    <div class="d-flex justify-content-center rounded-circle align-items-center bg-secondary bg-opacity-50  text-white"
                                        style="width: 30px; height: 30px;   background-color: {{ $withdrawal->bg_color ? $withdrawal->bg_color . ' !important' : '' }}">
                                        <img height="100%" width="100%" @class(['object-fit-contain', 'p-1' => $withdrawal instanceof \App\Models\CashoutRequest])
                                            src="{{ $withdrawal->method_image ? \Storage::url($withdrawal->method_image) : $withdrawal->user->avatar() }}"
                                            alt="{{ $withdrawal->name }}">
                                    </div>
                                    <div class="d-flex flex-column text-start align-items-start">
                                        <span class="mb-0 small">{{ $withdrawal->user->username }}</span>
                                        <h6 class="text-secondary pb-0 mb-0">{{ $withdrawal->updated_at->diffForHumans() }}
                                        </h6>
                                    </div>

                                    <div class="p-1">
                                        <span
                                            class="rounded badge bg-secondary bg-opacity-50 text-white d-flex align-items-center gap-1"
                                            :style="is_coin == '1' ? 'line-height: 0' : ''">
                                            <img src="{{ asset('assets/img/coin.png') }}" x-show="is_coin == '1'"
                                                width="11px" alt="">
                                            <span
                                                x-text="is_coin == '1' ? '{{ number_format($withdrawal->amount) }}' : '{{ '$' . to_money_str($withdrawal->amount) }}'"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@script


<script>
    window.addEventListener('render-live-cashouts', () => {
        try {
            document.querySelectorAll('.tooltip').forEach((el) => {
                el.remove();
            });

            setTimeout(() => {
                try {
                    document.querySelectorAll('[tooltip="true"]').forEach((el) => {
                        new bootstrap.Tooltip(el);
                    });
                } catch (e) {
                    console.error(e);
                }
            }, 100);

        } catch (e) {
            console.error(e);
        }
    });
</script>
@endscript