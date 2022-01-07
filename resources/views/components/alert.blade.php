<!-- Modal -->
<div class="modal fade" id="alertId" tabindex="-1" role="dialog" aria-labelledby="alertTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-uppercase">{{ Session::has('success') ? 'Success' : 'OOPS' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                @if (Session::has('success'))
                    <div>
                        <i class="fas fa-check-circle fa-10x text-success"></i>
                    </div>
                    <h4>{{ Session::get('success') }}</h4>
                @else
                    <div>
                        <i class="fas fa-exclamation-triangle fa-10x text-danger"></i>
                    </div>
                    <h4>{{ Session::get('error') }}</h4>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    let modal = new bootstrap.Modal(document.querySelector('#alertId'));
    modal.show();
    setTimeout(async () => {
        await modal.hide();
    }, 2000);
</script>
