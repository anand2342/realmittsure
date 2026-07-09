@extends('frontend.layouts.master')

@section('content')
<div class="articalBlog">
    <div class="container">
        <div class="row">
            <div class="col-md-7 px-md-2">
                <div class="row">
                    <div class="col-md-12 mb-3 mb-md-0">
                        @php
                        $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)
                            ->where('type', 'blog')
                            ->first();
                    @endphp
                        <div class="">
                            <figure class="blogImg">
                                <a href=""><img src="{{ Storage::url('uploads/blog/' .$image->attachment_file) }}" alt=""></a>
                            </figure>
                            <h4>{{$blog->title}}</h4>
                            <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                <span><img src="{{ asset('frontend/images/icon-eye.svg') }}" alt="" width="14">{{ $blog->views_count }}</span>
                                {{-- <span><img src="{{ asset('frontend/images/icon-clock.svg') }}" alt="" width="14"> 25 Min</span> --}}
                                <span><img src="{{ asset('frontend/images/icon-calender.svg') }}" alt="" width="14"> {{ dateConvert($blog->published_at, 'd, M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-5 ps-md-3">
                <span class="d-block text-primary fw-semibold mb-3">Popular Articles</span>
                <ul class="recentBlogList">
                    @foreach ($popular_blogs as $popular_blog )
                    <li>
                        <strong>{{$popular_blog->title}}</strong>
                        <a href="{{ route('blog.details', ['slug' => $popular_blog->slug]) }}">Learn More</a>
                    </li>
                    @endforeach
                    
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="BlogtextMain">
    <div class="container">
        {{-- <p>The world of gaming is a vast, vibrant universe that transcends boundaries, captivates the mind, and
            ignites the imagination. For those who find solace in pixels, adventure in virtual worlds, and joy in
            controller clicks, gaming isn't just a pastime—it's a way of life. Whether you're a seasoned gamer, a
            casual player, or someone curious about this electrifying realm, there's a place for you in this
            thrilling landscape. </p>
        <strong>The Magic of Gaming</strong>
        <p>Gaming isn't merely about defeating foes or solving puzzles; it's an immersive experience that transports
            you to realms where anything is possible. From exploring ancient ruins to commanding starships, the
            possibilities are as boundless as the human imagination. It's a medium where stories unfold, emotions
            are felt, and connections are forged across continents and cultures.</p>
        <strong>A Diverse Universe</strong>
        <p>One of the most beautiful aspects of gaming is its diversity. It spans genres, eras, and styles, catering
            to every taste imaginable. Are you an adrenaline junkie craving the rush of fast-paced action? Dive into
            the world of shooters or hack-and-slash adventures. Seeking a mental challenge? Puzzle games and
            strategy simulations await your intellect. Perhaps you prefer the emotional rollercoaster of
            narrative-driven experiences—there's a game for that too.</p>
        <strong>The Rise of Indie Games</strong>
        <p>While blockbuster titles often steal the spotlight, the rise of indie games has been a revelation. These
            smaller, often passion-fueled creations have brought innovation and creativity to the forefront. With
            unique storytelling, breathtaking art styles, and gameplay mechanics that push the boundaries, indie
            games continue to surprise and delight gamers worldwide. </p>
        <strong>Gaming Communities: Where Connections Flourish </strong>
        <p>Gaming isn't just about the pixels on the screen; it's about the people behind the controllers or
            keyboards. The camaraderie fostered in gaming communities is unparalleled. Whether it's strategizing
            with teammates in a multiplayer game or sharing tips and tricks on forums, these interactions create
            bonds that transcend geographical barriers, uniting individuals in a shared love for gaming.</p>
        <strong>The Future of Gaming</strong>
        <p>As technology continues to evolve, so does the gaming landscape. From the advent of virtual reality to
            the promise of augmented reality experiences, the future holds endless possibilities. Games are becoming
            more immersive, more accessible, and more intertwined with our daily lives than ever before. In a world
            where monotony sometimes reigns, gaming stands as a beacon of excitement, creativity, and endless
            potential. It's a realm where anyone can become a hero, explore fantastical realms, or simply unwind
            after a long day. So, to all the gamers out there—keep exploring, keep conquering, and keep embracing
            the magic of gaming. And to those yet to join this incredible journey—welcome, for an adventure of a
            lifetime awaits.</p>
            <p> Let the games continue!</p>
        <p> Introducing Mittlearn's comprehensive two-part course tailored for aspiring game developers, focused on
            harnessing the power of Unity—the industry-leading platform for creating captivating games. Whether
            you're a beginner eager to take your first steps or an intermediate developer aiming to refine your
            skills, this course is designed to equip you with the tools, knowledge, and expertise needed to bring
            your gaming visions to life.</p> --}}

            {!! $blog->body !!}
    </div>
</div>
@endsection
