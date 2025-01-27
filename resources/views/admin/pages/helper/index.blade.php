<x-app-layout>

    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Helper</h3>
                            </div>

                            <div class="nk-block-tools ms-auto d-flex align-items-center">
                                <ul class="nk-block-tools g-3 mb-0">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#helperModal"
                                            class="btn btn-icon btn-primary d-md-none">
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#helperModal"
                                            class="btn btn-primary d-none d-md-inline-flex">
                                            <em class="icon ni ni-plus"></em><span>Add Helper</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @include('admin.forms.create-helper-modal')
                        </div>
                    </div>

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($helpers as $helper)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $helper->fname }}</td>
                                                <td>{{ $helper->lname }}</td>
                                                <td>{{ $helper->mobile_no }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <!-- View -->
                                                                <li>
                                                                    <a
                                                                        href="{{ route('helper.show', $helper->helper_id) }}">
                                                                        <em class="icon ni ni-eye"></em><span>View</span>
                                                                    </a>
                                                                    <!-- Edit -->
                                                                <li>
                                                                    <a
                                                                        href="{{ route('helper.edit', $helper->helper_id) }}">
                                                                        <em class="icon ni ni-pen"></em><span>Edit</span>
                                                                    </a>
                                                                </li>
                                                                <!-- Delete -->
                                                                <li>
                                                                    <form
                                                                        action="{{ route('helper.destroy', $helper->helper_id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-link">
                                                                            <em
                                                                                class="icon ni ni-trash"></em><span>Delete</span>
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

