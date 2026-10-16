{{-- Contact and privacy request form (same fields). Params: $form, $white --}}
<div class="fui-i" id="form">
    @include('site.forms.open', ['form' => $form, 'white' => $white ?? false, 'formId' => $form])
        <div class="fui-row">
            @include('site.forms.field', ['formId' => $form, 'name' => 'first_name', 'label' => __('site.forms.first_name'), 'required' => true])
            @include('site.forms.field', ['formId' => $form, 'name' => 'last_name', 'label' => __('site.forms.last_name'), 'required' => true])
        </div>
        <div class="fui-row">
            @include('site.forms.field', ['formId' => $form, 'name' => 'email', 'type' => 'email', 'label' => __('site.forms.email'), 'required' => true])
            @include('site.forms.field', ['formId' => $form, 'name' => 'phone', 'type' => 'tel', 'label' => __('site.forms.phone'), 'required' => true])
        </div>
        <div class="fui-row">
            @include('site.forms.field', ['formId' => $form, 'name' => 'address', 'label' => __('site.forms.address')])
            @include('site.forms.field', ['formId' => $form, 'name' => 'country', 'label' => __('site.forms.country')])
        </div>
        <div class="fui-row">
            @include('site.forms.field', ['formId' => $form, 'name' => 'message', 'type' => 'textarea', 'label' => __('site.forms.message'), 'required' => true, 'placeholder' => __('site.forms.placeholders.message')])
        </div>
        <div class="fui-btn-wrapper">
            <button type="submit" class="fui-btn fui-submit btn primary">{{ __('site.forms.submit') }}</button>
        </div>
    </form>
</div>
