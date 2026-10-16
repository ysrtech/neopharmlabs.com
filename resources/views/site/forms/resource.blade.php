{{-- Gated download form. Params: $resource --}}
<div class="fui-i" id="form">
    @php($hasFile = (bool) $resource->file('download'))
    @include('site.forms.open', ['form' => 'resource', 'formId' => 'resource', 'successMessage' => $hasFile ? __('site.forms.download_success') : __('site.forms.download_pending')])
        <input type="hidden" name="subject_id" value="{{ $resource->id }}">
        <div class="fui-row">
            @include('site.forms.field', ['formId' => 'resource', 'name' => 'first_name', 'label' => __('site.forms.first_name'), 'required' => true, 'placeholder' => __('site.forms.placeholders.first_name')])
            @include('site.forms.field', ['formId' => 'resource', 'name' => 'last_name', 'label' => __('site.forms.last_name'), 'required' => true, 'placeholder' => __('site.forms.placeholders.last_name')])
        </div>
        <div class="fui-row">
            @include('site.forms.field', ['formId' => 'resource', 'name' => 'email', 'type' => 'email', 'label' => __('site.forms.email'), 'required' => true, 'placeholder' => __('site.forms.placeholders.email')])
        </div>
        <div class="fui-btn-wrapper">
            <button type="submit" class="fui-btn fui-submit btn primary">{{ $resource->button_label ?: __('site.resources.download_now') }}</button>
        </div>
    </form>
</div>
