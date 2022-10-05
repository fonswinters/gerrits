@extends('master')

@section('title', 'Styleguide')

@section('content')

    <div class="l-contain  u-space-mt6  u-space-mb8">

        <h1>Default heading - H1</h1>
        <h2>Default heading - H2</h2>
        <h3>Default heading - H3</h3>
        <h4>Default heading - H4</h4>
        <h5>Default heading - H5</h5>
        <h6>Default heading - H6</h6>

        <div class="s-text  u-space-mt3">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus accusantium commodi
                doloremque
                fugiat, id nulla sapiente vel. Ab accusamus adipisci architecto consectetur culpa debitis dolor
                dolorum
                est eum explicabo laborum libero natus non praesentium quaerat quam reiciendis rem sapiente sunt
                tempora
                temporibus totam vitae, voluptatum. Consequatur consequuntur cumque deserunt repellat.
            </p>
            <ul>
                <li class="u-fw-black">black - Abore mollitia recusandae sapiente sed. Explicabo, hic.</li>
                <li class="u-fw-extra-bold">extra-bold - Kolimus hic ipsum iure molestias necessitatibus sequi sint tempora?</li>
                <li class="u-fw-bold">bold - Otque, sit, soluta! Blanditiis eveniet laborum maxime nihil saepe tempore.</li>
                <li class="u-fw-semi-bold">semi-bold - Morporis dolorem eum itaque necessitatibus neque obcaecati omnis.</li>
                <li class="u-fw-medium">medium - Menim fugiat minus nostrum quam quis ullam, veritatis!</li>
                <li class="u-fw-regular">regular - Abore mollitia recusandae sapiente sed. Explicabo, hic.</li>
                <li class="u-fw-light">light - Kolimus hic ipsum iure molestias necessitatibus sequi sint tempora?</li>
                <li class="u-fw-extra-light">extra-light - Otque, sit, soluta! Blanditiis eveniet laborum maxime nihil saepe tempore.</li>
                <li class="u-fw-thin">thin - Morporis dolorem eum itaque necessitatibus neque obcaecati omnis.</li>
            </ul>
        </div>




        <div class="l-block  u-space-mt6  u-space-mb6  s-text">
            <h4 class="u-color-secondary-500">Buttons</h4>
            <div class="u-flex  u-flex--gap">
                @include('components.button', [
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowRight',
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowLeft',
					'iconPos' => 'before',
					'buttonText' => 'Label',
				])
            </div>

            <h4 class="u-color-secondary-500">Ghost buttons</h4>
            <div class="u-flex  u-flex--gap">
                @include('components.button', [
					'modifiers' => ['ghost'],
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowRight',
					'modifiers' => ['ghost'],
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowLeft',
					'modifiers' => ['ghost'],
					'iconPos' => 'before',
					'buttonText' => 'Label',
				])
            </div>

            <div class="u-bg-primary-500  u-space-p2  s-text  s-text--on-dark">
                <h4 class="u-color-neutral-0">Ghost + on-dark buttons</h4>
                <div class="u-flex  u-flex--gap">
                    @include('components.button', [
						'modifiers' => ['ghost', 'on-dark'],
						'buttonText' => 'Label',
					])
                    @include('components.button', [
						'icon' => 'arrowRight',
						'modifiers' => ['ghost', 'on-dark'],
						'buttonText' => 'Label',
					])
                    @include('components.button', [
						'icon' => 'arrowLeft',
						'modifiers' => ['ghost', 'on-dark'],
						'iconPos' => 'before',
						'buttonText' => 'Label',
					])
                </div>
            </div>

            <h4 class="u-color-secondary-500">Text buttons</h4>
            <div class="u-flex  u-flex--gap">
                @include('components.button', [
					'modifiers' => ['text'],
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowRight',
					'modifiers' => ['text'],
					'buttonText' => 'Label',
				])
                @include('components.button', [
					'icon' => 'arrowLeft',
					'modifiers' => ['text'],
					'iconPos' => 'before',
					'buttonText' => 'Label',
				])
            </div>

            <div class="u-bg-primary-500  u-space-p2  s-text  s-text--on-dark">
                <h4 class="u-color-neutral-0">Text + on-dark buttons</h4>
                <div class="u-flex  u-flex--gap">
                    @include('components.button', [
						'modifiers' => ['text', 'on-dark'],
						'buttonText' => 'Label',
					])
                    @include('components.button', [
						'icon' => 'arrowRight',
						'modifiers' => ['text', 'on-dark'],
						'buttonText' => 'Label',
					])
                    @include('components.button', [
						'icon' => 'arrowLeft',
						'modifiers' => ['text', 'on-dark'],
						'iconPos' => 'before',
						'buttonText' => 'Label',
					])
                </div>
            </div>
        </div>


    </div>

@endsection