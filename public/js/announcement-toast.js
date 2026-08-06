function announcementToast() {
    return {
        toasts: [],
        lastSeenId: 0,
        pollTimer: null,

        init() {
            const stored = parseInt(localStorage.getItem('sh_last_seen_announcement_id') || '0', 10);
            this.lastSeenId = isNaN(stored) ? 0 : stored;

            this.establishBaseline().then(() => {
                this.pollTimer = setInterval(() => this.checkForNew(), 4000);
            });
        },

        async establishBaseline() {
            // First-ever visit (no stored watermark yet): treat all currently
            // active announcements as already seen, so we only toast for ones
            // posted from now on, not the entire backlog.
            if (this.lastSeenId > 0) {
                return;
            }

            try {
                const response = await fetch('/api/announcements');
                if (!response.ok) return;
                const announcements = await response.json();
                const maxId = announcements.reduce((max, a) => Math.max(max, a.id), 0);
                this.lastSeenId = maxId;
                localStorage.setItem('sh_last_seen_announcement_id', String(maxId));
            } catch (error) {
                console.error('Announcement toast: failed to establish baseline', error);
            }
        },

        async checkForNew() {
            try {
                const response = await fetch('/api/announcements');
                if (!response.ok) return;
                const announcements = await response.json();

                const fresh = announcements
                    .filter(a => a.id > this.lastSeenId)
                    .sort((a, b) => a.id - b.id);

                if (fresh.length === 0) return;

                fresh.forEach(a => this.show(a));

                const maxId = Math.max(this.lastSeenId, ...fresh.map(a => a.id));
                this.lastSeenId = maxId;
                localStorage.setItem('sh_last_seen_announcement_id', String(maxId));
            } catch (error) {
                console.error('Announcement toast: poll failed', error);
            }
        },

        show(announcement) {
            const toastId = 'toast-' + announcement.id + '-' + Date.now();
            this.toasts.push({
                toastId,
                id: announcement.id,
                title: announcement.title,
                content: announcement.content,
                priority: announcement.priority || 'normal',
            });

            setTimeout(() => this.dismiss(toastId), 8000);
        },

        dismiss(toastId) {
            this.toasts = this.toasts.filter(t => t.toastId !== toastId);
        },

        priorityClasses(priority) {
            if (priority === 'urgent') return 'border-red-500 bg-red-50';
            if (priority === 'high') return 'border-orange-500 bg-orange-50';
            return 'border-green-500 bg-green-50';
        },

        viewAnnouncement() {
            window.location.href = '/dashboard?section=announcements';
        }
    };
}
