import "./style.css"
import Header from "../Header";
import Footer from "../Footer";
import Content from "../Content";
import Counter from "../Counter";

function Page(props) {
    return <div>
        <Header />

        <Counter />


        <Content />

        <Footer />
    </div>
}

export default Page;