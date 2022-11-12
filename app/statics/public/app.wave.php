<wv:view ngApp={taskApp} ngCtrl={taskAppCtrl}>
    <wv:comp.Container>
        <wv:comp.Card>
            <wv:comp.CardHead {app_name} />
            <wv:comp.Spinner>
                <div class="spinner-border text-danger" role="status">
                    <span class="sr-only">Loading....</span>
                </div>
            </wv:comp.Spinner>
            <wv:comp.TaskList>
                <wv:comp.Task deleteIcon={fa fa-trash} updateIcon={fa fa-edit} />
            </wv:comp.TaskList>
        </wv:comp.Card>
        <wv:comp.Modal targetId={modalSaveTask} saveModal/>
        <wv:comp.Modal targetId={modalUpdateTask} !saveModal/>
    </wv:comp.Container>
</wv:view>