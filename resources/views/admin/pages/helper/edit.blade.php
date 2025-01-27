<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <h3>Edit Helper</h3>
                    <form action="{{ route('helper.update', $helper->helper_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" id="fname" name="fname" class="form-control"
                                value="{{ $helper->fname }}">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" id="lname" name="lname" class="form-control"
                                value="{{ $helper->lname }}">
                        </div>
                        <div class="form-group">
                            <label for="mobile_no">Phone</label>
                            <input type="text" id="mobile_no" name="mobile_no" class="form-control"
                                value="{{ $helper->mobile_no }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('helper.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

