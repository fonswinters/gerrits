<div class="c-file-upload  js-file-upload">
    <div class="c-file-upload__wrapper">
        <ul class="c-file-upload__list  js-file-upload__list"></ul>
    </div>
    <div class="c-file-upload__main">
        <input class="c-file-upload__input  js-file-upload__input" id="file" name="file" type="file" accept="image/*,.pdf,.doc,.docx,.odt">
        @include('components.button', [
            'buttonClasses' => 'js-file-upload__button',
            'icon' => 'plus',
            'modifiers' => ['ghost'],
            'buttonText' => __('vacancies.formUploadCV'),
        ])
        <span class="c-file-upload__note">(Word of pdf, max 10 MB)</span>
    </div>
</div>