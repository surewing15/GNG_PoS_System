{{-- <script>
    function confirmation(id, type) {
        var userResponse = confirm("Are you sure?\nOnce deleted, you will not be able to recover this record!");
        if (userResponse) {
            $.ajax({
                url: '/api/delete',
                type: 'POST',
                data: {
                    push_id: id,
                    push_type: type
                },
                success: function(data) {
                    window.location.href = data;
                },
                error: function(err) {
                    alert(err);
                }
            });
        } else {

        }
    }
</script> --}}
<div class="nk-footer">
    <div class="container-fluid">
        <div class="nk-footer-wrap">
            <div class="moving-text">
                3GLG CHICKEN PRODUCING &copy; 2024
                <a target="_blank" style="color: #b4543d"> 🫡 Sta Cruz</a>
                | All Rights Reserved | <span id="date-time"></span>
                | Developer: Sherwin Victor Pablo Aleonar 📞 <a style="color: #b4543d;">+63
                    993 551 0319</a>
            </div>
        </div>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById("date-time").textContent = now.toLocaleDateString('en-US', options);
    }
    setInterval(updateDateTime, 3000);
    updateDateTime();
</script>

<style>
    .moving-text {
        white-space: nowrap;
        display: inline-block;
        position: relative;
        animation: moveText 10s linear infinite;
    }

    @keyframes moveText {
        from {
            transform: translateX(100%);
        }

        to {
            transform: translateX(-100%);
        }
    }
</style>
