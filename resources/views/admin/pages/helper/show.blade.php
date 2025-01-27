<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <h3>Helper Details</h3>
                    <ul>
                        <li><strong>First Name:</strong> {{ $helper->fname }}</li>
                        <li><strong>Last Name:</strong> {{ $helper->lname }}</li>
                        <li><strong>Phone:</strong> {{ $helper->mobile_no }}</li>
                    </ul>
                    <a href="{{ route('helper.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
