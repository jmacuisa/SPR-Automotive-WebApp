    <!-- Modal -->
    <div id="createModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-xl w-full">
            <h3 id="modalTitle" class="text-lg font-semibold mb-4">Add New Transaction</h3>
            <form id="createTransactionForm">
                <div id="modalBody" class="h-[28rem] pl-4 overflow-y-scroll">
                    <input type="hidden" id="transactionId" name="id">
                    <!-- Form fields as you have defined -->
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Processed By</label>
                        <input type="text" id="user_id" name="user_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="mechanic_id" class="block text-sm font-medium text-gray-700">Mechanic</label>
                        <input type="text" id="mechanic_id" name="mechanic_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
                        <input type="text" id="code" name="code"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="client_name" class="block text-sm font-medium text-gray-700">Client Name</label>
                        <input type="text" id="client_name" name="client_name"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="contact" class="block text-sm font-medium text-gray-700">Contact</label>
                        <input type="text" id="contact" name="contact"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" id="email" name="email"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea id="address" name="address" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" id="amount" name="amount"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end border-t-1 pt-2">
                    <button type="button" id="closeModal"
                        class="bg-gray-500 text-white px-4 py-2 rounded-md mr-2">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal -->
    <div id="viewModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h3 class="text-lg font-semibold mb-4">Transaction Details</h3>
            <div id="transactionDetails">
                <!-- Details will be dynamically added here -->
            </div>
            <div class="flex justify-end">
                <button type="button" id="closeViewModal"
                    class="bg-gray-500 text-white px-4 py-2 rounded-md">Close</button>
            </div>
        </div>
    </div>
