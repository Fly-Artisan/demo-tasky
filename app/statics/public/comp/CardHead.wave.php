<div class="row">
    <div class="col-6">
        <a href="@url(':i')">
            <h2 class='mb-3' style="font-family: fantasy !important;">
                {~ {:val.app_name} ~}
                &nbsp;
                <i class="fa fa-pencil"></i>
            </h2>
        </a>
    </div>
    <div class="col-3"></div>
    <div class="col-3">
        <button
            class="btn btn-success btn-round"
            style="{{dtBasicStyle}}"
            data-toggle="modal"
            data-target="#modalSaveTask"
            ng-click="focusToAdd()"
        >
            Add <i class="fa fa-plus"></i>
        </button>
    </div>
</div>
