<x-app-layout>
    <style>
        .progress-container {
            width: 90%;
            background-color: #f3f3f3;
            border-radius: 5px;
            overflow: hidden;
            margin: 0 auto;
        }

        .progress-bar {
            height: 10px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            border-radius: 5px 0 0 5px;
        }
    </style>
    <div class="station-page main main-bg question-page">
        <div class="mb-3 branding-container">
            @include('components.branding')
        </div>

        <div class="progress-container">
            <div class="progress-bar" style="width: 50%;"></div>
        </div>
        <div class="question">
            @include('components.question-section')
        </div>
    </div>
</x-app-layout>
