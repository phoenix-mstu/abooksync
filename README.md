Audio Book To Text Sync
=======================

This program is written as an experiment of syncing an audio book to it's text. So we can show a text of this book on a screen and highlight current word.
For this perpose we recognize the audio with [CMU Sphinx](http://cmusphinx.sourceforge.net/):

```bash
pocketsphinx_continuous -infile $name.wav -hmm ./pocketsphinx-0.8/model/hmm/en_US/hub4wsj_sc_8k -lm ./pocketsphinx-0.8/model/lm/en_US/hub4.5000.DMP -backtrace yes -beam 1e-20 -pbeam 1e-20 -lw 2.0  -dict ./pocketsphinx-0.8/model/lm/en_US/hub4.5000.dic -time 1 > $name-parsed.txt
```

After running the command above we have a regignized text in this format:

```bash
the 1540.700000 1540.840000 0.509371
eyes 1540.850000 1541.390000 0.185112
to(2) 1541.400000 1541.590000 0.684926
double 1541.600000 1541.990000 0.963190
blow 1542.000000 1542.520000 0.255136
where(2) 1542.530000 1543.020000 0.024443
...
```

Of course there are many mistakes. And then we run this program to filter them and get result.

## Simplofied algorithm description

We have text A (original book text) and text B (recognized text). First we get a sequence in text A and then look for this sequence in text B. Usually we find several occurances of the sequence. So we remember all of them, then we get next sequence in text A and so on.

When we done we build directed graph of this text fragments. We link sequence 1 to squence 2 if:

* sequence 1 is left to sequence 2
* the sequences are not cover each other

so there are so many links that usually we dont have enough memory to keep them (that's why the algorithm is optimized)

After creating links we use Bellman-Ford algirithm to find the longest path in the graph - this path is our result.


