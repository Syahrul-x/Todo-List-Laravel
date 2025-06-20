<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-semibold text-white mb-6">Edit Reminder</h1>
    
    <div class="bg-[#2c2e31] p-4 rounded-lg mb-4">
        <p class="text-gray-400">Untuk <?= htmlspecialchars($reminder['entry_type']) ?>:</p>
        <p class="text-lg font-bold text-white"><?= htmlspecialchars($reminder['title']) ?></p>
    </div>

    <form action="?c=calendar&m=update" method="POST">
        <input type="hidden" name="reminder_id" value="<?= htmlspecialchars($reminder['id']) ?>">

        <div class="mb-4">
            <label for="reminder_time" class="block text-gray-300 mb-2">Reminder Date and Time</label>
            <input type="datetime-local" id="reminder_time" name="reminder_time" required
                   value="<?= date('Y-m-d\TH:i', strtotime($reminder['reminder_time'])) ?>"
                   class="w-full p-3 bg-[#3a3a3a] border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="mb-6">
            <label for="message" class="block text-gray-300 mb-2">Optional Message</label>
            <textarea id="message" name="message" rows="3"
                      class="w-full p-3 bg-[#3a3a3a] border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="e.g., Prepare presentation slides..."><?= htmlspecialchars($reminder['message']) ?></textarea>
        </div>

        <div class="flex items-center justify-between">
            <button type="button" onclick="document.getElementById('delete-form').submit();" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">
                Delete
            </button>
            
            <div class="flex items-center space-x-4">
                <a href="?c=calendar&m=index" class="text-gray-400 hover:text-white">Cancel</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
    
    <form id="delete-form" action="?c=calendar&m=delete" method="POST" class="hidden">
        <input type="hidden" name="reminder_id" value="<?= htmlspecialchars($reminder['id']) ?>">
    </form>
</div>