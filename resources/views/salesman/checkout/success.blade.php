@include("salesman/include/header")




<style>
  :root{
    --bg:#f6fbf9;
    --card:#ffffff;
    --muted:#6b7280;
    --accent:#10b981; /* green */
    --accent-600:#059669;
    --glass: rgba(255,255,255,0.65);
    --shadow: 0 8px 30px rgba(16,24,40,0.08);
    font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  }



  .card{
    background:var(--card);
    width:100%;
    max-width:980px;
    border-radius:16px;
    box-shadow:var(--shadow);
    overflow:hidden;
    display:grid;
    grid-template-columns: 420px 1fr;
  }

  /* Left panel (visual) */
  .left{
    padding:40px;
    background: linear-gradient(180deg, #ffffff 0%, #f0fdf9 100%);
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    gap:20px;
  }

  .badge{
    width:108px;
    height:108px;
    border-radius:999px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:linear-gradient(180deg,var(--accent) 0%, var(--accent-600) 100%);
    box-shadow: 0 8px 20px rgba(16,185,129,0.24);
    transform:scale(1);
  }

  .badge svg{ width:56px; height:56px; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.08)); }

  h1{ margin:0; font-size:22px; letter-spacing:-0.2px; }
  p.lead{ margin:0; color:var(--muted); font-size:14px; text-align:center; }

  .order-meta{
    width:100%;
    margin-top:6px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    justify-content:center;
  }

  .chip{
    background:var(--glass);
    padding:8px 12px;
    border-radius:999px;
    font-weight:600;
    color:var(--accent-600);
    font-size:13px;
    box-shadow: inset 0 -1px 0 rgba(255,255,255,0.6);
  }

  /* Right panel (details) */
  .right{
    padding:28px 32px;
  }

  .top-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
  }

  .order-info{
    display:flex;
    flex-direction:column;
    gap:6px;
  }

  .order-number{
    font-weight:700;
    letter-spacing:-0.3px;
  }

  .muted{ color:var(--muted); font-size:13px; }

  .summary{
    margin-top:18px;
    display:flex;
    gap:18px;
    align-items:flex-start;
    flex-wrap:wrap;
  }

  .summary .box{
    flex:1 1 180px;
    background:#fbfffc;
    border-radius:10px;
    padding:12px;
    border:1px solid rgba(16,24,40,0.03);
  }

  .box h4{ margin:0 0 6px 0; font-size:13px; }
  .money{ font-weight:800; font-size:18px; }

  .items{
    margin-top:18px;
    border-radius:10px;
    overflow:hidden;
    background:linear-gradient(180deg,#fff,#fff);
    border:1px solid rgba(16,24,40,0.03);
  }

  .item{
    display:flex;
    gap:12px;
    padding:12px;
    align-items:center;
  }

  .item + .item{ border-top:1px solid rgba(16,24,40,0.04); }

  .thumb{
    width:60px;height:60px;border-radius:8px;background:#f3f4f6;display:flex;
    align-items:center;justify-content:center;font-weight:700;color:#9ca3af;
  }

  .item-info{ flex:1; }
  .item-title{ font-weight:600; font-size:14px; }
  .item-sub{ color:var(--muted); font-size:13px; }

  .right-actions{
    margin-top:18px;
    display:flex;
    gap:12px;
    align-items:center;
  }

  .btn{
    border:0;
    padding:10px 14px;
    border-radius:10px;
    cursor:pointer;
    font-weight:700;
    font-size:14px;
  }

  .btn-primary{
    background:linear-gradient(90deg,var(--accent) 0%, var(--accent-600) 100%);
    color:white;
    box-shadow: 0 8px 22px rgba(16,185,129,0.18);
  }

  .btn-ghost{
    background:transparent;
    color:var(--accent-600);
    border:1px solid rgba(5,150,105,0.12);
  }

  .small{ font-size:13px; padding:8px 10px; border-radius:8px; font-weight:600; }

  /* footer note */
  .note{ margin-top:14px; color:var(--muted); font-size:13px; }

  /* responsive */
  @media (max-width:860px){
    .card{ grid-template-columns: 1fr; }
    .left{ padding:28px 20px; }
    .right{ padding:20px; }
  }

  /* Print */
  @media print{
    body{ background:white; }
    .btn, .badge{ display:none; }
    .card{ box-shadow:none; border-radius:0; }
  }

  /* confetti pieces (positioned absolute) */
  .confetti {
	    pointer-events: none;
	    position: fixed;
	    inset: 0;
	    overflow: visible;
	    width: 100%;
	    height: 100%;
	}
  .confetti span{
    position:absolute;
    width:10px;height:14px;
    opacity:0.95;
    transform-origin:center;
    border-radius:2px;
  }
</style>
	

	
	
    <div class="container mt-3 mt-lg-4 mt-xl-5" id="main-content">
        <div class="row gx-3">



        	<div class="wrap">
			    <div class="card" role="region" aria-label="Order success">
				      <div class="left">
				        <div class="badge" aria-hidden="true">
				          <!-- check icon -->
				          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
				            <path d="M20 7L9 18L4 13" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
				          </svg>
				        </div>

				        <h1 class="text-center">Thank you — your order is confirmed!</h1>			        
				      </div>
			    </div>

		      <!-- confetti container -->
		      <div class="confetti" id="confetti" aria-hidden="true"></div>
		    </div>
		  </div>




        </div>
    </div>







<script>
  // small helper: generate confetti pieces
  (function runConfetti(){
    const colors = ['#10B981','#F97316','#60A5FA','#F43F5E','#A78BFA'];
    const confettiContainer = document.getElementById('confetti');
    if(!confettiContainer) return;

    // create 30 pieces
    for(let i=0;i<30;i++){
      const el = document.createElement('span');
      el.style.background = colors[i % colors.length];
      el.style.left = Math.random()*100 + '%';
      el.style.top = (Math.random()*20 + 2) + '%';
      el.style.transform = 'rotate('+ (Math.random()*360) +'deg)';
      el.style.opacity = (0.7 + Math.random()*0.3);
      el.style.width = (8 + Math.random()*8) + 'px';
      el.style.height = (12 + Math.random()*10) + 'px';
      el.style.animation = 'fall ' + (2.6 + Math.random()*2.2) + 's linear forwards';
      confettiContainer.appendChild(el);
    }

    // keyframes
    const s = document.createElement('style');
    s.innerHTML = `@keyframes fall {
      0% { transform: translateY(-10vh) rotate(0deg); opacity:1; }
      100% { transform: translateY(110vh) rotate(360deg); opacity:0.85; }
    }`;
    document.head.appendChild(s);

    // remove after animation ends to keep DOM clean
    setTimeout(()=> confettiContainer.innerHTML = '', 6000);
  })();

  // simple interactions - replace with real navigation
  document.getElementById('viewOrderBtn').addEventListener('click', ()=>{
    // navigate to order page (customize)
    window.location.href = '/orders/594812';
  });
  document.getElementById('continueBtn').addEventListener('click', ()=>{
    window.location.href = '/';
  });

  // Example: you can set dynamic values from server-side/template/JS
  // document.getElementById('orderId').textContent = '....';
  // document.getElementById('amount').textContent = '...';
</script>


@include("salesman/include/footer")