$(document).ready(function () {
    var table = $("#example").DataTable({
        paging: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: "/transactions",
            type: "GET", // Use GET method for fetching data
        },
        columnDefs: [
            { className: "custom-align-left", targets: 2 }, // Apply custom alignment to the 'price' column (3rd column, zero-based index)
        ],
        columns: [
            { data: "processed_by" },
            { data: "mechanic" },
            { data: "code" },
            { data: "client_name" },
            { data: "contact" },
            { data: "email" },
            { data: "address" },
            { data: "amount" },
            {
                data: "status",
                render: function (data) {
                    var statusText = "";
                    var statusClass = "";

                    if (data == 1) {
                        statusText = "Active";
                        statusClass =
                            "bg-green-500 text-white border border-green-700"; // Tailwind class for green background with a border
                    } else if (data == 2) {
                        statusText = "Inactive";
                        statusClass =
                            "bg-red-500 text-white border border-red-700"; // Tailwind class for red background with a border
                    }
                    return `<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">${statusText}</span>`;
                },
            },

            {
                data: null,
                defaultContent: `
                    <div class="flex space-x-2">
                        <button class="view text-gray-500 hover:text-gray-700 mr-2" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="edit text-green-500 hover:text-gray-700 mr-2" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button class="delete text-red-600 hover:text-red-800" title="Delete">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                `,
            },
        ],
        pageLength: 10, // Set default page length
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ], // Page length options
    });

    // Handle Form Submission
    $("#createTransactionForm").on("submit", function (e) {
        e.preventDefault();

        var transactionId = $("#transactionId").val();
        var url = transactionId ? "/transactions/" + transactionId : "/transactions"; // URL for update or create
        var method = transactionId ? "POST" : "POST"; // Using POST; _method will handle it

        var formData = new FormData(this); // Using FormData to include files

        if (transactionId) {
            formData.append("_method", "PUT"); // Laravel requires this for PUT requests
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: url,
            method: method, // Change this to method
            data: formData,
            contentType: false, // Prevent jQuery from setting the content-type header
            processData: false, // Prevent jQuery from processing the data
            success: function (response) {
                $("#createModal").hide();
                Swal.fire({
                    icon: "success",
                    title: transactionId ? "Updated!" : "Added!",
                    text: transactionId
                        ? "The transaction has been updated successfully."
                        : "The transaction has been added successfully.",
                }).then(() => {
                    // $('#createModal').addClass('hidden');
                    $("#createTransactionForm")[0].reset();
                    table.ajax.reload(); // Reload the DataTable with new data
                });
            },
            error: function (xhr) {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text:
                        "Error occurred while saving the transaction. " +
                        xhr.responseText,
                });
            },
        });
    });

    // Handle Create Button Click
    $("#createButton").on("click", function () {
        $("#createModal").removeClass("hidden");
        $("#modalTitle").text("Create Transaction");
        $("#transactionId").val(""); // Clear ID for new transaction
        $("#createTransactionForm")[0].reset(); // Reset form fields
    });

    // Handle Close Modal Click
    $("#closeModal").on("click", function () {
        $("#createModal").addClass("hidden");
    });

    // Close the view modal
    $("#closeViewModal").on("click", function () {
        $("#viewModal").addClass("hidden");
    });

    // Click outside to close open menus and modals
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".view, .action-button").length) {
            $(".action-menu").addClass("hidden");
            $("#viewModal").addClass("hidden");
        }
    });

    // Handle action button clicks
    $("#example").on("click", "button", function (e) {
        e.preventDefault();
        var action = $(this).attr("class");
        var rowData = table.row($(this).parents("tr")).data();

        switch (true) {
            case action.includes("view"):
                $("#transactionDetails").html(`
                    <p><strong>Processed By:</strong> ${rowData.processed_by}</p>
                    <p><strong>Mechanic:</strong> ${rowData.mechanic}</p>
                    <p><strong>Code:</strong> ${rowData.code}</p>
                    <p><strong>Client Name:</strong> ${rowData.client_name}</p>
                    <p><strong>Contact:</strong> ${rowData.contact}</p>
                    <p><strong>Email:</strong> ${rowData.email}</p>
                    <p><strong>Address:</strong> ${rowData.address}</p>
                    <p><strong>Amount:</strong> ${rowData.amount}</p>
                    <p><strong>Status:</strong> ${
                        rowData.status ? "Active" : "Inactive"
                    }</p>
                `);
                $("#viewModal").removeClass("hidden");
                break;
            case action.includes("edit"):
                $("#modalTitle").text("Edit Transaction");
                $("#transactionId").val(rowData.id);
                $("#user_id").val(rowData.user_id);
                $("#mechanic_id").val(rowData.mechanic_id);
                $("#code").val(rowData.code);
                $("#client_name").val(rowData.client_name);
                $("#contact").val(rowData.contact);
                $("#email").val(rowData.email);
                $("#address").val(rowData.address);
                $("#amount").val(rowData.amount);
                $("#status").val(rowData.status);
                $("#createModal").removeClass("hidden");
                break;
            case action.includes("delete"):
                // SweetAlert2 confirmation
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with the delete operation
                        $.ajax({
                            url: "/transactions/" + rowData.id, // Adjust URL as needed
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr(
                                    "content"
                                ),
                            },
                            success: function (response) {
                                Swal.fire(
                                    "Deleted!",
                                    "The transaction has been deleted.",
                                    "success"
                                );
                                table.ajax.reload(); // Reload DataTable
                            },
                            error: function (xhr) {
                                Swal.fire(
                                    "Error!",
                                    "There was an issue deleting the transaction.",
                                    "error"
                                );
                            },
                        });
                    }
                });
                break;
        }
        $(this).closest(".action-menu").addClass("hidden");
    });
});
