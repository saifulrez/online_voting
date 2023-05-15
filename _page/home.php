<script>
    var electionList, searchElectionKeyword = null

    function ElectionList() {
        $.get({
            url: '/API/election.php',
            data: {
                keyword: searchElectionKeyword
            }
        }).done((response) => {
            const cacheElectionList = electionList;
            try {
                electionList = JSON.parse(response);
            } catch (e) {
                console.log(e);
                return;
            }
            $('#election_list').empty();
            if (cacheElectionList) {
                if (cacheElectionList.length >= 1) {
                    cacheElectionList.forEach((election) => {
                        clearInterval(election.interval);
                    })
                }
            }

            function electionComponent(election) {
                $('#election_list').append('<div class="col-md-6 col-lg-6 col-xl-4"><div class="card"><img class="card-img-top text-center" style="max-height: 300px;" src="/asset/img/election/' + election.img.src + '" alt="' + election.img.alt + '"><div class="card-body"><h5 class="card-title">' + election.title + '</h5><div class="card-text">' + election.description + '</div></div><a class="text-center" href="?page=detail&election_id=' + election.election_id + '"><button class="btn btn-' + election.btn.class + ' col-10 px-2">' + election.btn.text + '</button></a><div class="card-footer text-right"><small class="text-muted">' + election.footer.state + '<div class="d-inline" id="election-countdown-' + election.election_id + '"></div></small></div></div></div>');
            }

            function electionCountdown(electionId, timeCountdown) {
                if (timeCountdown) {
                    const countDownTime = new Date(timeCountdown).getTime();
                    const currentTime = new Date().getTime();
                    const timeRemaining = countDownTime - currentTime;

                    var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                    if (timeRemaining < 0) {
                        ElectionList();
                    } else {
                        const electionCountdown = days + " วัน " + String(hours).padStart(2, "0") + " ชั่วโมง " + String(minutes).padStart(2, "0") + " นาที " + String(seconds).padStart(2, "0") + " วินาที";
                        $('#election-countdown-' + electionId).text(electionCountdown);
                        // console.log(electionCountdown);
                    }
                }
            }
            electionList.forEach((election) => {
                let timeCountdown, btnClass, btnText, footerState = null;
                switch (election.election_state) {
                    case 1:
                        timeCountdown = election.start_time;
                        btnClass = 'warning';
                        btnText = 'ระบบยังไม่เปิดให้ลงคะแนนในขณะนี้';
                        footerState = 'จะเปิดการลงคะแนนใน ';
                        break;
                    case 2:
                        timeCountdown = election.end_time;
                        btnClass = 'primary';
                        btnText = 'คลิกเพื่อไปลงคะแนน';
                        footerState = 'จะปิดการลงคะแนนใน ';
                        break;
                    case 3:
                        timeCountdown = election.announcement_time;
                        btnClass = 'danger';
                        btnText = 'ระบบปิดการลงคะแนนแล้ว';
                        footerState = 'จะประกาศผลคะแนนใน ';
                        break;
                    case 4:
                        timeCountdown = false;
                        btnClass = 'success';
                        btnText = 'คลิกเพื่อดูผลคะแนน';
                        footerState = '';
                        break;
                }
                election.img = {
                    src: election.img,
                    alt: election.title
                }
                election.btn = {
                    class: btnClass,
                    text: btnText
                }
                election.footer = {
                    state: footerState
                }
                electionComponent(election);
                election.interval = timeCountdown ? setInterval(electionCountdown, 1000, election.election_id, timeCountdown) : null;
            })
        })
    }

    $(document).ready(() => {
        ElectionList();
        setInterval(ElectionList, 1000 * 60);
    })
</script>
<div class="row" id="election_list"></div>